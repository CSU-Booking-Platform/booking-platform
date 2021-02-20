<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
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
        'date_restrictions' => $this->dateRestrictions->mapWithKeys(function ($r) {
          return [$r->id=>[
            'min' => $r->pivot->min_days_advance,
            'max' => $r->pivot->max_days_advance,
          ]
          ];
        })
      ]);
    }
}
