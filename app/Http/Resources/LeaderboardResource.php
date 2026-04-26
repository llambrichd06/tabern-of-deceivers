<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LeaderboardResource extends JsonResource
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
            'user_id' => $this->user_id,
            'wins' => $this->wins,
            'losses' => $this->losses,
            'matches' => $this->matches,
            'points' => $this->points,
            'user' => new UserResource($this->whenLoaded('user')), //whenLoaded gets the relationship with that name if it loads
        ];
    }
}
