<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'room_code' => $this->room_code,
            'state' => $this->state,
            'private' => $this->private,
            'host_id' => $this->host_id,
            'players' => UserResource::collection($this->whenLoaded('players')), //whenLoaded gets the relationship with that name if it loads
            'host' => new UserResource($this->whenLoaded('host')),
        ];
    }
}
