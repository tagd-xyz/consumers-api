<?php

namespace App\Http\V1\Requests\Tagd;

use Illuminate\Foundation\Http\FormRequest;

class Store extends FormRequest
{
    public const TAGD_SLUG = 'tagdSlug';

    public const RESELLER_ID = 'resellerId';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            self::TAGD_SLUG => 'string|required',
            self::RESELLER_ID => 'string|required',
        ];
    }
}
