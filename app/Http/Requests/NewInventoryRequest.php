<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewInventoryRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'string'
        ];
    }

    /**
     * Return with modified response when this request fails
     * 
     * @param  array  $errors An array of validation errors
     * @return \Illuminate\Http\Response
     */
    public function response (array $errors) {
        return response()->json([
            'message' => 'Cannot create inventory due to bad data',
            'errors' => $errors
        ], 400);
    }
}
