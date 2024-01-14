<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class ProjectCreateRequest extends FormRequest
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
            'title' => 'required|max:20|min:2|string',
            'description' => 'nullable|max:50|min:2',
            'Assigner' => 'required'


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
            'title.required' => 'A title is required',
            'title.max:20' => 'A title is big',
            'title.min:2' => 'A title is small',
            'title.string' => 'A title is not string',

            'description.max:50' => 'A description is big',
            'description.min:2' => 'A description is small',

            'Assigner.required' => 'A Assigner is required',
        ];
    }
}
