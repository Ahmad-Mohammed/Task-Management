<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class TaskCreateRequest extends FormRequest
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
            'Title' => 'required|max:20|min:2|string',
            'Description' => 'nullable|max:50|min:2',
            'Status' => 'required',
            'Due_Date' => 'required|date_format:Y-m-d',
            'project_id' => 'required',
            'Assigner' => 'required',
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
            'Title.required' => 'A Title is required',
            'Title.max:20' => 'A Title is big',
            'Title.min:2' => 'A Title is small',
            'Title.string' => 'A Title is not string',

            'Description.max:50' => 'A Description is big',
            'Description.min:2' => 'A Description is small',

            'Status.required' => 'A Status is required',

            'Due_Date.required' => 'A Due_Date is required',
            'Due_Date.date_format:Y-m-d' => 'error format ',

            'Assigner.required' => 'A Assigner is required',

        ];
    }
}
