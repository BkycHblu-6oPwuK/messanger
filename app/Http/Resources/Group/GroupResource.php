<?php

namespace App\Http\Resources\Group;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class GroupResource extends JsonResource
{
    public $preserveKeys = true;

    public function toArray(Request $request): array
    {
        $authId = auth()->user()->id;
        $groupData = [];

        if ($this->type == 'group') {
            $usersId = [];
            foreach ($this->members as $member) {
                $usersId[] = $member->user_id;
            }
            $groupData[$this->id] = [
                'id' => $this->id,
                'users_id'     => $usersId,
                'name'         => $this->name,
                'avatar'       => $this->avatar,
                'description'  => $this->description,
                'creator'      => $this->creator_id
            ];
        } else {
            foreach ($this->members as $member) {
                $user = $member->user;
                if ($authId != $user->id) {
                    $groupData[$this->id] = [
                        'id' => $this->id,
                        'id_user'     => $user->id,
                        'name'   => $user->name,
                        'avatar' => $user->avatar
                    ];
                }
            }
        }
        return $groupData;
    }
}
