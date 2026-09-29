<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'category' => $this->category,
            'image' => $this->image ? \Storage::url($this->image) : null,
            'date' => $this->event_date?->format('Y-m-d'),
            'time' => $this->event_time,
            'location' => $this->location,
            'organizer' => $this->organizer,
            'phone' => $this->phone,
            'attendees' => $this->attendees,
            'status' => $this->status,
            'featured' => $this->is_featured,
            'highlights' => $this->highlights ?? [],
            'speakers' => $this->speakers ?? [],
            'outcomes' => $this->outcomes ?? [],
            'registration' => [
                'fee' => $this->registration_fee ?? 'Free',
                'deadline' => $this->registration_deadline?->format('Y-m-d'),
                'contact' => $this->contact_email ?? 'events@ieyda.org',
                'link' => $this->registration_link,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
