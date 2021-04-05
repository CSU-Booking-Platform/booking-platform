<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'created_diff' => $this->created_at->diffForHumans(),
            'updated_diff' => $this->updated_at->diffForHumans(),
            'user' => [
                'name' => $this->user->name,
                'email' => $this->user->email,
                'profile_photo_url' => $this->user->profile_photo_url,
            ],
        ]);
    }
}
