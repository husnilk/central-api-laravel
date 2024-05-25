<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudyPlanDetailUpdateRequest extends FormRequest
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
            'study_plan_id' => ['required'],
            'class_id' => ['required'],
            'status' => ['required', 'integer'],
            'in_transcript' => ['required', 'integer'],
            'weight' => ['nullable', 'numeric'],
            'grade' => ['nullable', 'numeric'],
            'class_course_id' => ['required', 'integer', 'exists:class_courses,id'],
        ];
    }
}
