<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
class GalleryResource extends JsonResource {
    public function toArray($request) {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->image && str_starts_with($this->image, 'http') ? $this->image : ($this->image && file_exists(storage_path('app/public/' . $this->image)) ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents(storage_path('app/public/' . $this->image))) : 'https://via.placeholder.com/400x300?text=No+Image'),
            'category' => $this->category,
            'event_date' => $this->event_date,
            'order' => $this->order,
            'active' => (bool) $this->is_active,
            'date' => $this->created_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
