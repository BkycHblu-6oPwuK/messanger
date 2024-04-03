<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\DeletedMessages;

class Message extends Model
{
    use HasFactory;
    protected $guarded = false;
    protected $table = 'messages';

    public function gallery()
    {
        return $this->hasMany(Gallery::class);
    }

    public function parseToTimeInMessage()
    {
        $date = Carbon::parse($this->created_at)->format('H.i');
        return $date;
    }

    public function deleteMessage($method)
    {
        if($method == 'real'){
            return $this->delete();
        } else {
            $data = [
                'user_id' => auth()->user()->id,
                'message_id' => $this->id,
                'chat_group_id' => $this->chat_group_id
            ];
            $deleted = DeletedMessages::create($data);
            return $deleted->id ? true : false;
        }
    }

    public static function destroyMessages($idsRealDelete, $idsPseudoDelete, $chat_id, $method)
    {
        if($method == 'real' && !empty($idsRealDelete)){
            static::destroy($idsRealDelete);
        }
        if(!empty($idsPseudoDelete)){
            $data = [];
            $userId = auth()->user()->id;
            foreach($idsPseudoDelete as $id){
                $data[] = [
                    'user_id' => $userId,
                    'message_id' => $id,
                    'chat_group_id' => $chat_id
                ];
            }
            DeletedMessages::insert($data);
        }
        return true;
    }
}
