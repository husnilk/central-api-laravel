<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudyPlanUpdateRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'student_id' => ['required'],
            'periode_id' => ['required'],
            'counselor_id' => ['required'],
            'status' => ['required', 'integer'],
            'registered_at' => ['required', 'date'],
            'gpa' => ['required', 'numeric', 'between:-999999.99,999999.99'],
            'period_id' => ['required', 'integer', 'exists:periods,id'],
        ];
    }
}
