<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MeetingNoticeResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'meeting_type' => $this->meeting_type,
            'summary' => $this->summary,
            'details' => $this->details,
            'starts_at' => $this->starts_at?->toIso8601String(),
            'ends_at' => $this->ends_at?->toIso8601String(),
            'location' => $this->location,
            'action_label' => $this->action_label,
            'action_url' => $this->action_url,
            'priority' => $this->priority,
        ];
    }
}
