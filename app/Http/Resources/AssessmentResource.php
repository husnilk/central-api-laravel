<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssessmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'assessment_detail_id' => $this->assessment_detail_id,
            'study_plan_detail_id' => $this->study_plan_detail_id,
            'grade' => $this->grade,
            'studyPlanDetail' => StudyPlanDetailResource::make($this->whenLoaded('studyPlanDetail')),
        ];
    }
}
