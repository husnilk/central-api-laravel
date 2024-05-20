<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudyPlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'periode_id' => $this->periode_id,
            'counselor_id' => $this->counselor_id,
            'status' => $this->status,
            'registered_at' => $this->registered_at,
            'gpa' => $this->gpa,
            'period_id' => $this->period_id,
            'studyPlanDetails' => StudyPlanDetailCollection::make($this->whenLoaded('studyPlanDetails')),
        ];
    }
}
