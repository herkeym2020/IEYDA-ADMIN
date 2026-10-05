<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
class NewsResource extends JsonResource {
    public function toArray($request) {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'image' => $this->image && str_starts_with($this->image, 'http') ? $this->image : ($this->image && file_exists(storage_path('app/public/' . $this->image)) ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents(storage_path('app/public/' . $this->image))) : 'https://via.placeholder.com/600x400?text=No+Image'),
            'published_at' => $this->published_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'category' => $this->category,
            'author' => $this->author,
            'read_time' => $this->read_time,
            'readTime' => $this->read_time,
            'is_featured' => (bool) $this->is_featured,
            'is_published' => (bool) $this->is_published,
            'tags' => $this->tags ?? [],
        ];
    }
}
