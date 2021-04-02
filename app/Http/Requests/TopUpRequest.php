<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TopUpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'amount_rupiah' => 'required|integer|min:1',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
    */
    public function messages()
    {
        $messages = [
            'amount_rupiah.min' => 'The minimum top up is 1',
            'amount_rupiah.max' => 'The maximum top up is 0',
        ];
        return $messages;
    }
}
