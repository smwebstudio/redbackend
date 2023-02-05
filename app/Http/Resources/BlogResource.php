<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
//    private mixed $main_image_file_path;
//    private mixed $content_arm;
//    private mixed $id;
//    private mixed $title_arm;

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
            'created_at' => $this->created_on->toDateTimeString(),
            'title' => $this->title_arm,
            'content' => $this->content_arm,
            'picture' => $this->main_image_file_path ? 'https://proinfo.am/uploadsWithWaterMark/'.$this->main_image_file_path : "https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460__480.png",
        ];
    }
}
