<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Estate extends JsonResource
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
            'price' => $this->full_price,
            'old_price' => $this->old_price,
            'full_address' => $this->full_address,
            'image' => $this->main_image_file_path ? 'https://proinfo.am/uploadsWithWaterMark/'.$this->main_image_file_path : 'https://i0.wp.com/lanecdr.org/wp-content/uploads/2019/08/placeholder.png',
        ];
    }
}
