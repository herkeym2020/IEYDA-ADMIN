<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeamMemberResource extends JsonResource
{
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'type' => $this->type,
            'name' => $this->name,
            'position' => $this->position,
            'image' => $this->image ? \Storage::url($this->image) : null,
            'bio' => $this->bio,
            'email' => $this->email,
            'phone' => $this->phone,
            'location' => $this->location,
            'achievements' => $this->achievements,
            'level' => $this->level,
            'social_links' => $this->social_links,
            'order' => $this->order,
            'priority' => $this->priority,
            'is_active' => (bool) $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        // Add type-specific fields
        if ($this->type === 'grand_patron') {
            $data['salute'] = $this->salute;
            $data['awards'] = $this->awards;
        }

        if (in_array($this->type, ['executive_present', 'executive_pioneering'])) {
            $data['executive_type'] = $this->executive_type;
            $data['term'] = $this->term;
            $data['department'] = $this->department;
        }

        return $data;
    }
}
