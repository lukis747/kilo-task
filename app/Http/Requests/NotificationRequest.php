<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class NotificationRequest extends FormRequest
{
    public function rules()
    {
        return [
            'notification_type' => 'required|string'
        ];
    }

    public function authorize()
    {
        return true;
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(['errors' => $validator->errors()], 400)
        );

        parent::failedValidation($validator);

    }
}
