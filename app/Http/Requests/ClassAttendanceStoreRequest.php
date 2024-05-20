<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClassAttendanceStoreRequest extends FormRequest
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
            'study_plan_detail_id' => ['required'],
            'class_meeting_id' => ['required'],
            'meet_no' => ['required', 'integer'],
            'device_id' => ['nullable', 'string'],
            'device_name' => ['nullable', 'string'],
            'lattitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'attendance_status' => ['required', 'integer'],
            'need_attention' => ['required', 'integer'],
            'information' => ['required', 'string'],
        ];
    }
}
