<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatGroups extends Model
{
    use HasFactory;
    protected $guarded = false;
    protected $table = 'chat_groups';

    public function members()
    {
        return $this->hasMany(GroupMembers::class, 'chat_group_id', 'id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'group_members', 'chat_group_id', 'user_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'chat_group_id', 'id')
            ->whereNotIn('id', function ($query) {
                $query->select('message_id')
                    ->from('deleted_messages')
                    ->where('deleted_messages.user_id', auth()->user()->id)
                    ->whereRaw('deleted_messages.message_id = message_id')
                    ->where('deleted_messages.chat_group_id', $this->id);
            });
    }
}
