<?php

namespace App\Http\Controllers\Chats;

use App\Events\StoreMessageEvent;
use App\Http\Requests\Api\GetUsersRequest;
use App\Http\Requests\Messages\StoreRequest;
use App\Http\Requests\Messages\UpdateRequest;
use App\Http\Resources\Group\GroupResource;
use App\Models\ChatGroups;
use App\Models\GroupMembers;
use App\Models\Message;
use Illuminate\Support\Str;

class ChatsController extends BaseChatController
{
    public function index()
    {
        $groups = $this->service->getGroup();
        $previewMessages = $this->service->getPreviewMessages($groups);
        $friends = $this->service->getPreviewFriend();
        return inertia('Chats/Index', compact('previewMessages','groups','friends'));
    }

    public function show(ChatGroups $chat)
    {
        $data = $this->service->show($chat);
        $chat = GroupResource::make($chat)->resolve();
        $chat = array_reduce($chat, function($carry,$item){
           return $carry + $item;
        },[]);
        $groups = $data['groups'];
        $friends = $data['previewFriends'];
        $previewMessages = $data['previewMessages'];
        $messages = $data['messages'];
        $pagination = $data['pagination'];
        return inertia('Chats/Index', compact('chat','groups','friends', 'previewMessages', 'messages','pagination'));
    }

    public function details(ChatGroups $chat)
    {
        if (Str::startsWith(request()->path(), 'api')) {
            dd('ты лох');
        }
        $chat = GroupResource::make($chat)->resolve();
        $chat = array_reduce($chat, function($carry,$item){
            return $carry + $item;
         },[]);
        return inertia('Chats/Details',compact('chat'));
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $data['sender_id'] = auth()->user()->id;
        $message = $this->service->store($data);
        //broadcast(new StoreMessageEvent($message));
        return redirect()->route('chats.show', $data['chat_group_id']);
    }

    public function update(Message $message, UpdateRequest $request)
    {
        $data = $request->validated();
        unset($data['files']);
        $message->update($data);
        return redirect()->route('chats.show', $message->chat_group_id);
    }

    public function delete(Message $message)
    {
        $id = $message->chat_group_id;
        $message->delete();
        return redirect()->route('chats.show', $id);
    }

    public function getMessages(ChatGroups $chat,$page)
    {
        $data = $this->service->getMessages($chat,$page);
        $messages = $data['messages'];
        return response()->json($messages);
    }
}
