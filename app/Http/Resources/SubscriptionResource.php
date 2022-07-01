<?php

namespace App\Http\Resources;

use App\Http\Resources\UserResource;
use App\Http\Resources\WebsiteResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
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
            'user' => new UserResource($this->user),
            'website' => new WebsiteResource($this->website),
        ];
    }
}
