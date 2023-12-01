<?php

namespace App\Http\Controllers\Chats;

use App\Http\Requests\Group\StoreRequest;
use App\Models\ChatGroups;
use App\Models\GroupMembers;

class GroupController extends BaseChatController
{
    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $data['creator_id'] = auth()->user()->id;
        $members = $data['members'];
        $members[]['id'] = auth()->user()->id;
        unset($data['members']);
        $group = ChatGroups::create($data);
        foreach($members as $member){
            GroupMembers::create([
                'chat_group_id' => $group->id,
                'user_id' => $member['id']
            ]);
        }
        return redirect()->route('chats.index');
    }
}
