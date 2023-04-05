<?php

namespace App\Http\V1\Requests\AccessRequest;

use Illuminate\Foundation\Http\FormRequest;

class Approve extends FormRequest
{
    public const CODE = 'code';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            self::CODE => 'string|required',
        ];
    }
}
