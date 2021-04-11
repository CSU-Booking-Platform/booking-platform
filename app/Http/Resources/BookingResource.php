<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
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
            'status' => ucfirst($this->status),
            'created_diff' => $this->created_at->diffForHumans(),
            'updated_diff' => $this->updated_at->diffForHumans(),
            'reference' => collect($this->reference)->map(function ($file) {
                return [
                    'name' => collect(explode('/', $file))->last(),
                    'link' => url("storage/$file"),
                ];
            }),
            'reservations' => $this->reservations->map(function ($r) {
                return [
                    'room' => $r->room,
                    'start_time' => $r->start_time->isoFormat('LLL'),
                    'end_time' => $r->end_time->isoFormat('LLL'),
                    'start_time_full' => $r->start_time,
                    'end_time_full' => $r->end_time,
                ];
            }),
            'reviewers' => $this->reviewers->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'profile_photo_url' => $user->profile_photo_url,
                ];
            }),
            'comments' => $this->comments->map(function ($comment) {
                return new CommentResource($comment);
            })
        ]);
    }
}
