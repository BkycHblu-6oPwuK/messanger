<?php

namespace App\Http\Requests\Messages;

use Illuminate\Foundation\Http\FormRequest;

class DestroyRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'ids' => 'required|array',
            'ids.*' => 'integer',
            'chat_id' => 'integer'
        ];
    }
}
/*
'body' => 'required_without_all:files,deletesFiles|nullable|string',
'deletesFiles' => 'required_without_all:files,body|array',
'files' => 'required_without_all:body,deletesFiles|array',
*/
