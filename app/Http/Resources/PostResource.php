<?php

namespace App\Http\Resources;

use App\Http\Resources\WebsiteResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'website' => new WebsiteResource($this->website),
            'title' => $this->title,
            'description' => $this->description,
            'main_content' => $this->body,
            'created_at' => $this->created_at,
        ];
    }
}
