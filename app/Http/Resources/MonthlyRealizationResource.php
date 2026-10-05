<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MonthlyRealizationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'month' => $this->month?->format('Y-m-d'),
            'title' => $this->title,
            'community_name' => $this->community_name,
            'lga' => $this->lga,
            'summary' => $this->summary,
            'details' => $this->details,
            'impact_metric' => $this->impact_metric,
            'image' => $this->image ? \Storage::url($this->image) : null,
            'priority' => $this->priority,
        ];
    }
}
