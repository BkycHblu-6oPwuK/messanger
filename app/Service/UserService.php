<?

namespace App\Service;

use App\Http\Resources\Users\AcceptedFriendResource;
use App\Http\Resources\Users\RequestFriendResource;
use App\Http\Resources\Users\UsersResource;
use App\Models\Friend;
use App\Models\User;

class UserService
{
    public function friendRequest()
    {
        $friend = Friend::where('friend_id',auth()->user()->id)->where('status','pending')->get();
        $friend = RequestFriendResource::collection($friend)->resolve();
        return $friend;
    }

    public function friends()
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
        $friends = AcceptedFriendResource::collection($friends)->resolve();
        return $friends;
    }

    public function show($user)
    {
        $isFriend = $this->hasStatusWith($user->id,'accepted');
        $isPending = $this->hasStatusWith($user->id,'pending');
        return compact('isFriend','isPending');
    }

    public function hasStatusWith($id,$status)
    {
        $friend = Friend::where(function ($query) use($id,$status){
            $query->where('user_id',$id)->where('friend_id',auth()->user()->id)->where('status',$status);
        })->orWhere(function($query) use($id,$status){
            $query->where('friend_id',$id)->where('user_id',auth()->user()->id)->where('status',$status);
        })->exists();
        return $friend;
    }

    public function getUsersForIds($ids)
    {
        $users = User::whereIn('id',$ids)->select('id','name','avatar')->get();
        $users = UsersResource::collection($users)->resolve();
        return $users;
    }
}
