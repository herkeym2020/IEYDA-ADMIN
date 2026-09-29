<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
class ProgramResource extends JsonResource {
    public function toArray($request) {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'category' => $this->category,
            'image' => $this->image ? \Storage::url($this->image) : null,
            'icon' => $this->icon,
            'beneficiaries' => $this->beneficiaries,
            'budget' => $this->budget,
            'duration' => $this->duration,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'objectives' => $this->objectives,
            'achievements' => $this->achievements,
            'partners' => $this->partners,
            'locations' => $this->locations,
            'progress' => $this->progress,
            'coordinator' => $this->coordinator,
            'order' => $this->order,
            'status' => $this->status,
            'is_active' => (bool) $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
