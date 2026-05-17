<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
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
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority'    => ['required', 'in:low,medium,high'],
            'status'      => ['required', 'in:pending,in_progress,completed'],
            'due_date'    => ['nullable', 'date'],
            'assigned_to' => ['required', 'exists:users,id'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required'       => 'A task title is required.',
            'title.max'            => 'The task title may not exceed 255 characters.',
            'priority.required'    => 'A priority level is required.',
            'priority.in'          => 'Priority must be low, medium, or high.',
            'status.required'      => 'A task status is required.',
            'status.in'            => 'Status must be pending, in_progress, or completed.',
            'due_date.date'        => 'Please provide a valid due date.',
            'assigned_to.required' => 'Please assign this task to a user.',
            'assigned_to.exists'   => 'The selected user does not exist.',
        ];
    }
}
