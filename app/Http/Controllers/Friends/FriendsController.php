<?php

namespace App\Http\Controllers\Friends;

use App\Http\Controllers\Controller;
use App\Http\Requests\Friends\StoreRequest;
use App\Models\ChatGroups;
use App\Models\Friend;
use App\Models\GroupMembers;
use App\Models\User;
use Illuminate\Http\Request;

class FriendsController extends Controller
{
    public function index()
    {
        return inertia('User/Index');
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $result = Friend::create($data);
        return response()->json($result);
    }

    public function accepted(Friend $friend)
    {
        $result = $friend->update([
            'status' => 'accepted',
        ]);
        $chat = ChatGroups::create([
            'type' => 'friends'
        ]);
        $user_ids = [$friend->friend_id, $friend->user_id];
        $chat->users()->attach($user_ids);
        return response()->json($result);
    }
}
