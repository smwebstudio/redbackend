<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BrokerReview extends JsonResource
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
            'message_text' => $this->message_text,
            'service_name' => $this->messageServiceName->name_arm,
            'sender_name' => $this->sender_name,
            'overall_rating' => $this->overall_rating,
            ];
    }
}
