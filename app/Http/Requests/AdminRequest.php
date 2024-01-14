<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class AdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'firstname' => 'required|string|min:5|max:20',
            'lastname' => 'required|string|min:5|max:20',
            'password' => 'required',
            'image' => 'nullable|size:10240|mimes:jpeg,png|max:60',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'data'   => null,
            'message'   =>  $validator->errors(),
            'status'      => JsonResponse::HTTP_BAD_REQUEST
        ]));
    }
    public function messages(): array
    {
        return [
            'firstname.required' => 'A firstname is required',
            'firstname.max:20' => 'A firstname is big',
            'firstname.min:2' => 'A firstname is small',
            'firstname.string' => 'A firstname is not string',

            'lastname.required' => 'A lastname is required',
            'lastname.max:20' => 'A lastname is big',
            'lastname.min:2' => 'A lastname is small',
            'lastname.string' => 'A lastname is not string',

            'password.required' => 'A password is required',

            'image.size:10240' => 'A image is not 10MB',
            'image.mimes:jpeg,png' => 'A image must be jpeg,png',
            'image.max:60' => 'A image name is big',

        ];
    }

}
