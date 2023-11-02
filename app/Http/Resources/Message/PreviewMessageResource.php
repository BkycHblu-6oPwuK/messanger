<?php

namespace App\Http\Resources\Message;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PreviewMessageResource extends JsonResource
{
    public $preserveKeys = true;

    public function toArray(Request $request): array
    {
        $data[$this->chat_group_id] = [
            'id' => $this->id,
            'body' => $this->body,
            'date' => $this->parseToTimeInMessage(),
        ];
        return $data;
    }
}
