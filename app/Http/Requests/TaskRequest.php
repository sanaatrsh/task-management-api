<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
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
            "description" => 'required|string',
            "status" => 'required|in:pending,in progress, completed',
            "assigned_to" => 'required|exists:users,id',
            "created_by" => 'required|exists:users,id',
            "due_date" => 'required|date',
        ];
    }
}
