<?php

namespace App\Http\Requests;

class CreateOrUpdateBookRequest extends BaseRequest
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
            'author_id' => 'required|exists:App\Models\Author,id',
            'title' => 'required|min:2|max:100',
            'annotation' => 'max:1000',
            'publication_date' => 'required|date_format:d-m-Y',
        ];
    }
}
