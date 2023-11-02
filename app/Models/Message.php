<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Message extends Model
{
    use HasFactory;
    protected $guarded = false;
    protected $table = 'messages';

    public function getGallaryForMessage()
    {
        $message = $this->hasMany(Gallery::class)->get();
        return $message;
    }

    public function parseToTimeInMessage()
    {
        $date = Carbon::parse($this->created_at)->format('H.i');
        return $date;
    }
}
