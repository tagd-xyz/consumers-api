<?php

namespace App\Http\V1\Requests\Item;

use Illuminate\Foundation\Http\FormRequest;

class Index extends FormRequest
{
    public const PER_PAGE = 'perPage';

    public const PAGE = 'page';

    public const DIRECTION = 'direction';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            self::PER_PAGE => 'numeric|min:1|max:9999',
            self::PAGE => 'numeric|min:1|max:9999',
            self::DIRECTION => 'string|in:asc,desc',
        ];
    }
}
