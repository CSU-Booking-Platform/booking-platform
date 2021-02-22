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
            // remove this when we update download to store reference to files, not just folder
            'reference' => empty($this->reference) ? [[
                'name' => 'thing.pdf',
                'link' => '/storage/6_1611766740_reference/a2.pdf',
            ]] : $this->reference,
            'reservations' => $this->reservations->map(function ($r) {
                return [
                    'room' => $r->room,
                    'start_time' => $r->start_time->isoFormat('LLL'),
                    'end_time' => $r->end_time->isoFormat('LLL'),
                    'start_time_full' => $r->start_time,
                    'end_time_full' => $r->end_time,
                ];
            })
        ]);
    }
}
