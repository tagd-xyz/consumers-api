<?php

namespace App\Http\V1\Requests\Notification;

use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
{
    public const IS_READ = 'isRead';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            self::IS_READ => 'boolean',
        ];
    }
}
