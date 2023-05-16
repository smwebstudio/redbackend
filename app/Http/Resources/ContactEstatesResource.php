<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactEstatesResource extends JsonResource
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
            'email' => $this->email,
            'full_name' => $this->full_name,
            'phone_1' => $this->phone_mobile_1,
            'user' => $this->user,
            'professions' => $this->user->professions,
            'communities' => $this->user->locationCommunities,
            'estateTypes' => $this->user->estateTypes,
            'messages' => BrokerReview::collection($this->user->messages->where('message_type_id', 4)),
            'rating' => $this->user->average_rating,
            'estates' => $this->user->broker_estates,
            'estates_count' => count($this->user->broker_estates),
            'profile_picture' => $this->user?->profile_picture_path ? $this->user->public_path : "https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460__480.png",
        ];
    }
}
