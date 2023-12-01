<?php

namespace App\Http\Resources\Users;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class PreviewFriendResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $userDetail = $this->user_id == auth()->user()->id ? $this->friend : $this->user;
        return Arr::only($userDetail->toArray(), ['id', 'name']);
    }
}
