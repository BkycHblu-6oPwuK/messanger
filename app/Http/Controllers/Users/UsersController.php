<?php

namespace App\Http\Controllers\Users;

use App\Http\Requests\Api\GetUsersRequest;
use App\Models\User;

class UsersController extends BaseUserController
{
    public function index()
    {
        $friendRequest = $this->service->friendRequest();
        $friends = $this->service->friends();
        return inertia('User/Index',compact('friendRequest','friends'));
    }

    public function show(User $user)
    {
        if($user->id == auth()->user()->id){
            return redirect()->route('users.index');
        }
        $data = $this->service->show($user);
        return inertia('User/Index',compact('user','data'));
    }


    public function getUsersForIds(GetUsersRequest $request)
    {
        $data = $request->validated();
        $users = $this->service->getUsersForIds($data['users_ids']);
        return response()->json($users);
    }
}
