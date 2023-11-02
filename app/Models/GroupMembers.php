<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMembers extends Model
{
    use HasFactory;
    protected $guarded = false;
    protected $table = 'group_members';

    public function user() {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
