<?php

namespace App\Http\V1\Requests\Item;

use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
{
    public const IS_ACTIVE = 'isActive';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            self::IS_ACTIVE => 'boolean',
        ];
    }
}
