<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudyPlanDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'study_plan_id' => $this->study_plan_id,
            'class_id' => $this->class_id,
            'status' => $this->status,
            'in_transcript' => $this->in_transcript,
            'weight' => $this->weight,
            'grade' => $this->grade,
            'class_course_id' => $this->class_course_id,
            'classAttendances' => ClassAttendanceCollection::make($this->whenLoaded('classAttendances')),
        ];
    }
}
