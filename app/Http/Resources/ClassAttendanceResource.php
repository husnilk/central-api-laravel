<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassAttendanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'study_plan_detail_id' => $this->study_plan_detail_id,
            'class_meeting_id' => $this->class_meeting_id,
            'meet_no' => $this->meet_no,
            'device_id' => $this->device_id,
            'device_name' => $this->device_name,
            'lattitude' => $this->lattitude,
            'longitude' => $this->longitude,
            'attendance_status' => $this->attendance_status,
            'need_attention' => $this->need_attention,
            'information' => $this->information,
            'classMeeting' => ClassMeetingResource::make($this->whenLoaded('classMeeting')),
        ];
    }
}
