<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
class HeroSlideResource extends JsonResource {
    public function toArray($request) {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'description' => $this->description,
            'badge' => $this->badge,
            'motto' => $this->motto,
            'yoruba_text' => $this->yoruba_text,
            'primary_image' => $this->primary_image ? \Storage::url($this->primary_image) : null,
            'overlay_image' => $this->overlay_image ? \Storage::url($this->overlay_image) : null,
            'ctas' => $this->ctas,
            'order' => $this->order,
            'is_active' => (bool) $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
