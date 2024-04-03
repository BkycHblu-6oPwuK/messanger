<?

namespace App\Service;

use App\Http\Resources\Users\PreviewFriendResource;
use App\Http\Resources\Group\GroupResource;
use App\Http\Resources\Message\MessageResource;
use App\Http\Resources\Message\PreviewMessageResource;
use App\Models\Friend;
use App\Models\Gallery;
use App\Models\Message;
use FFMpeg\FFProbe;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Request;
use Pawlox\VideoThumbnail\Facade\VideoThumbnail;

class ChatService
{
    public function show($chat)
    {
        $groups = $this->getGroup();
        $previewMessages = $this->getPreviewMessages($groups);
        $previewFriends = $this->getPreviewFriend();
        $data = $this->getMessages($chat);
        $messages = $data['messages'];
        $pagination = $data['pagination'];
        return compact('messages', 'previewMessages', 'previewFriends', 'pagination', 'groups');
    }

    public function store($data)
    {
        if (isset($data['files'])) {
            $files = $data['files'];
            unset($data['files']);
        }
        $message = Message::create($data);
        $data = [];

        if (!empty($files)) {
            $data = $this->addFiles($message->id, $files);
            $saveFile = Gallery::insert($data);
        }
        return $message;
    }

    public function addFiles($messageId, $files)
    {
        foreach ($files as $key => $file) {
            $dir = "/messages/{$messageId}";
            $saveFile = Storage::disk('public')->put($dir, $file);
            $assetPath = "/storage/{$saveFile}";
            $type = $file->getClientMimeType();
            $data[$key] = [
                'message_id' => $messageId,
                'media_path' => $assetPath,
                'original_name' => $file->getClientOriginalName(),
                'type' => $type,
            ];
            if (Str::of($type)->startsWith('video')) {
                $videoPath = Storage::disk('public')->path($saveFile);
                $thumbnail = $this->addThumbnail($dir, $videoPath);
                $data[$key]['preview_path'] = $thumbnail;
            }
        }
        return $data;
    }

    public function addThumbnail($dir, $videoPath)
    {
        $ffprobe = FFProbe::create();
        $duration = floor($ffprobe->format($videoPath)->get('duration'));
        $thumbName = Str::random(40) . '.jpg';
        VideoThumbnail::createThumbnail(
            $videoPath,
            Storage::disk('public')->path($dir),
            $thumbName,
            rand(0, $duration),
            1920,
            1080
        );
        $thumbnail = "/storage{$dir}/{$thumbName}";
        return $thumbnail;
    }

    public function update($message, $data)
    {
        $result = [];
        if (isset($data['files'])) {
            $files = $data['files'];
            unset($data['files']);
        }
        if (isset($data['deletesFiles'])) {
            $deletesFiles = $data['deletesFiles'];
            unset($data['deletesFiles']);
        }
        //if (isset($data['body'])) {
        $update = $message->update($data);
        //}
        $data = [];

        if (!empty($files)) {
            $data = $this->addFiles($message->id, $files);
            $result['files'] = $data;
            $update = Gallery::insert($data);
        }
        if (!empty($deletesFiles)) {
            $countFiles = Gallery::countFiles($message->id);
            $countEqual = count($deletesFiles) == $countFiles;
            $this->deletesFiles($deletesFiles, $countEqual);
            if ($countEqual && empty($update)) {
                $delete = $message->delete();
            }
        }
        if (empty($update) && empty($delete)) {
            $update = true;
        }
        $result['update'] = isset($update) ? $update : false;
        $result['delete'] = isset($delete) ? $delete : false;
        return $result;
    }

    public function delete($message, $method = 'real')
    {
        if ($method == 'real') {
            $idsFiles = Gallery::where('message_id', $message->id)->select('id')->get()->pluck('id')->toArray();
            if (!empty($idsFiles)) {
                $this->deletesFiles($idsFiles, true);
            }
        }
        return $message->deleteMessage($method);
    }

    public function destroy($data, $method = 'real')
    {
        $groupedIds = $this->getGroupIdsMessagesByUser($data['ids']);
        $idsPseudoDelete = [];
        $idsRealDelete = [];
        if($idsRealDelete = $groupedIds->pull(auth()->user()->id)){
            $idsRealDelete = $idsRealDelete->pluck('id')->toArray();
        }
        if(!$groupedIds->isEmpty()){
            $idsPseudoDelete = $groupedIds->collapse()->pluck('id')->toArray();
        }
        if ($method == 'real' && !empty($idsRealDelete)) {
            $idsFiles = Gallery::whereIn('message_id', $idsRealDelete)->select('id')->get()->pluck('id')->toArray();
            if (!empty($idsFiles)) {
                $this->deletesFiles($idsFiles, true);
            }
        }
        $result = Message::destroyMessages($idsRealDelete, $idsPseudoDelete, $data['chat_id'], $method);
        return $result;
    }

    public function getGroupIdsMessagesByUser($ids)
    {
        return Message::whereIn('id',$ids)->select('id','sender_id')->get()->groupBy('sender_id');
    }

    public function deletesFiles(array $idsFiles, $deleteDir = false)
    {
        $gallary = Gallery::whereIn('id', $idsFiles)->select('message_id', 'media_path', 'preview_path')->get();
        $files = [];
        foreach ($gallary as $file) {
            $files[$file->message_id][] = $file;
        }
        //$files = collect($files);
        foreach ($files as $key => $file) {
            $file = collect($file);
            $media = $file->pluck('media_path');
            $preview = $file->pluck('preview_path');
            $media = $media->map(function ($value) {
                return basename($value);
            });
            $preview = $preview->map(function ($value) {
                return basename($value);
            });
            $dir = "/messages/{$key}/";
            foreach ($media as $med) {
                Storage::disk('public')->delete($dir . $med);
            }
            foreach ($preview as $prew) {
                Storage::disk('public')->delete($dir . $prew);
            }
            if ($deleteDir) {
                Storage::disk('public')->deleteDirectory($dir);
            }
        }
        $delete = Gallery::destroy($idsFiles);
        return $delete;
    }

    public function getMessages($chat, $page = 1)
    {
        // PaginationPaginator::currentPageResolver(function () use ($page) {
        //     return $page;
        // });
        //$previousResolver = CursorPaginator::currentCursorResolver();
        if (is_string($page)) {
            CursorPaginator::currentCursorResolver(function () use ($page) {
                return $page;
            });
        }
        $authId = auth()->user()->id;
        $messages = $chat->messages()->orderBy('id', 'desc')->with('gallery')->cursorPaginate(100, ['*'], 'page', $page);
        $pagination = [
            //'count' => $messages->count(),
            //'cursor' => $messages->cursor(),
            //'getOptions' => $messages->getOptions(),
            //'hasPages' => $messages->hasPages(),
            //'hasMorePages' => $messages->hasMorePages(),
            //'getCursorName' => $messages->getCursorName(),
            //'items' => $messages->items(),
            //'nextCursor' => $messages->nextCursor(),
            'nextPage' => $this->getPageFromUrl($messages->nextPageUrl()),
            //'onFirstPage' => $messages->onFirstPage(),
            'onLastPage' => $messages->onLastPage(),
            'perPage' => $messages->perPage(),
            //'previousCursor' => $messages->previousCursor(),
            //'previousPageUrl' => $messages->previousPageUrl(),
            //'setCursorName' => $messages->setCursorName('newCursor'),
            //'url' => $messages->url($messages->cursor()),
        ];

        $messages = MessageResource::collection($messages)->resolve();
        //asort($messages);
        //$messages = array_values($messages);
        return compact('messages', 'pagination');
    }

    public function getPreviewFriend()
    {
        $authId = auth()->user()->id;
        $friends = Friend::where(function ($query) use ($authId) {
            $query->where('friend_id', $authId)->where('status', 'accepted');
        })->orWhere(function ($query) use ($authId) {
            $query->where('user_id', $authId)->where('status', 'accepted');
        })
            ->with(['user:id,name', 'friend:id,name'])
            ->select('user_id', 'friend_id')
            ->get();

        $friends = PreviewFriendResource::collection($friends)->resolve();
        return $friends;
    }

    public function getPageFromUrl($url)
    {
        if (isset($url)) {
            $request = Request::create($url);
            $url = $request->query('page');
        }
        return $url;
    }

    public function getGroup()
    {
        $groups = auth()->user()->chats;
        $groupsMembers = GroupResource::collection($groups)->resolve();
        $groupsMembers = array_reduce($groupsMembers, function ($carry, $item) {
            return $carry + $item;
        }, []);
        return $groupsMembers;
    }

    public function getPreviewMessages($groups)
    {
        $group_ids = array_keys($groups);

        $latestDates = Message::select('chat_group_id', DB::raw('MAX(created_at) as max_created_at'))
            ->whereIn('chat_group_id', $group_ids)
            ->groupBy('chat_group_id')
            ->get();

        $messages = $latestDates->map(function ($dateRecord) {
            return Message::where('chat_group_id', $dateRecord->chat_group_id)
                ->where('created_at', $dateRecord->max_created_at)
                ->first();
        })->filter();

        $messages = PreviewMessageResource::collection($messages)->resolve();
        $messages = array_reduce($messages, function ($carry, $item) {
            return $carry + $item;
        }, []);
        return $messages;
    }
}
