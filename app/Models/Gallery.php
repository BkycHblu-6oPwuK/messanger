<?php

namespace App\Models;

use BeyondCode\LaravelWebSockets\Statistics\Statistic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;
    protected $guarded = false;
    protected $table = 'gallery';

    public static function countFiles($messageId){
        return self::where('message_id',$messageId)->count();
    }
}
