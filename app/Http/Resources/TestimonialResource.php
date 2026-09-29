<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
class TestimonialResource extends JsonResource {
    public function toArray($request) {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'testimonial' => $this->content,
            'photo' => $this->image ? \Storage::url($this->image) : null,
            'active' => (bool) $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
