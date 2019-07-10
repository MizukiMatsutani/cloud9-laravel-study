<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PinResource;

class FriendResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nickname' => $this->nickname,
            'face_image_url' => 
                $this->image_path
                ? route('web.image.get', ['userId' => $this->id]) . '?t=' . $this->updated_at->getTimestamp()
                : null,
            'pin' => PinResource::make($this->whenLoaded('pin')),
        ];
    }
}
