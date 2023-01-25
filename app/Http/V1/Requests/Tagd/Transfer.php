<?php

namespace App\Http\V1\Requests\Tagd;

use Illuminate\Foundation\Http\FormRequest;

class Transfer extends FormRequest
{
    public const CONSUMER_ID = 'consumerId';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            self::CONSUMER_ID => 'string|required',
        ];
    }
}
