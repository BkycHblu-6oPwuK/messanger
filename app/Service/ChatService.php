<?

namespace App\Service;

use App\Http\Resources\Users\PreviewFriendResource;
use App\Http\Resources\Group\GroupResource;
use App\Http\Resources\Message\MessageResource;
use App\Http\Resources\Message\PreviewMessageResource;
use App\Models\Friend;
use App\Models\Gallery;
use App\Models\Message;
use Illuminate\Pagination\Paginator as PaginationPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        return compact('messages', 'previewMessages','previewFriends', 'pagination','groups');
    }

    public function store($data)
    {
        if (isset($data['files'])) {
            $files = $data['files'];
            unset($data['files']);
        }
        $message = Message::create($data);
        $data = [];
        if (isset($files)) {
            foreach ($files as $file) {
                $saveFile = Storage::disk('public')->put("/messages/{$message->id}", $file);
                $assetPath = asset('storage') . "/{$saveFile}";

                $data[] = [
                    'message_id' => $message->id,
                    'media_path' => $assetPath,
                    'original_name' => $file->getClientOriginalName(),
                    'type' => $file->getClientMimeType()
                ];
            }
            $saveFile = Gallery::insert($data);
        }
        return $message;
    }

    public function getMessages($chat, $page = 1)
    {
        PaginationPaginator::currentPageResolver(function () use ($page) {
            return $page;
        });
        $authId = auth()->user()->id;
        $messages = $chat->messages()->latest()->paginate(25);//;
        $pagination = [
            'perPage' => $messages->perPage(),
            'currentPage' => $messages->currentPage(),
            'total' => $messages->total(),
            'lastPage' => $messages->lastPage(),
        ];
        $messages = MessageResource::collection($messages)->resolve();
        asort($messages);
        $messages = array_values($messages);
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

    public function getGroup()
    {
        $groups = auth()->user()->chats;
        $groupsMembers = GroupResource::collection($groups)->resolve();
        $groupsMembers = array_reduce($groupsMembers, function($carry, $item) {
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
        $messages = array_reduce($messages, function($carry,$item){
            return $carry + $item;
        },[]);
        return $messages;
    }

}
