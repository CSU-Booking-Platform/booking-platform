<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class CreateBookingRequest extends UpdateBookingRequest
{

    /**
     * The key to be used for the view error bag.
     *
     * @var string
     */
    protected $errorBag = 'createBookingRequest';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(), [
            'room_id' => ['required', 'integer'],

            'reservations' => ['required'],
            'reservations.*.start_time' => ['required', 'date'],
            'reservations.*.end_time' => ['required', 'date'],

            'files' => [
                'required_if:event.food.self_catered,true',
                'required_if:event.alcohol,true',
                'required_if:event.children,true',
                'required_if:event.appliances,true',
                'required_if:event.bake_sale,true',
                //'mimes:pdf,doc,docx',
                'max:50000', // 50Mb
            ],
        ]);
    }

}
