<?php

namespace App\Services;

use App\Models\{News, Event, Program, TeamMember, Testimonial, Community, Gallery, HeroSlide, ContactMessage};
use Illuminate\Support\Collection;

class AdminSearchService
{
    public function search(string $query, ?string $type = null): Collection
    {
        $query = trim($query);
        
        if (empty($query) || strlen($query) < 2) {
            return collect();
        }

        $results = collect();

        if (!$type || $type === 'news') {
            $results = $results->merge($this->searchNews($query));
        }

        if (!$type || $type === 'events') {
            $results = $results->merge($this->searchEvents($query));
        }

        if (!$type || $type === 'programs') {
            $results = $results->merge($this->searchPrograms($query));
        }

        if (!$type || $type === 'team') {
            $results = $results->merge($this->searchTeamMembers($query));
        }

        if (!$type || $type === 'communities') {
            $results = $results->merge($this->searchCommunities($query));
        }

        if (!$type || $type === 'gallery') {
            $results = $results->merge($this->searchGallery($query));
        }

        if (!$type || $type === 'testimonials') {
            $results = $results->merge($this->searchTestimonials($query));
        }

        if (!$type || $type === 'hero') {
            $results = $results->merge($this->searchHeroSlides($query));
        }

        if (!$type || $type === 'messages') {
            $results = $results->merge($this->searchMessages($query));
        }

        return $results->sortByDesc('created_at');
    }

    private function searchNews(string $query): Collection
    {
        return News::where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'title' => $item->title,
                'type' => 'News',
                'icon' => 'newspaper',
                'url' => route('admin.news.edit', $item->id),
                'created_at' => $item->created_at,
            ]);
    }

    private function searchEvents(string $query): Collection
    {
        return Event::where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'title' => $item->title,
                'type' => 'Event',
                'icon' => 'calendar-alt',
                'url' => route('admin.events.edit', $item->id),
                'created_at' => $item->created_at,
            ]);
    }

    private function searchPrograms(string $query): Collection
    {
        return Program::where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'title' => $item->title,
                'type' => 'Program',
                'icon' => 'graduation-cap',
                'url' => route('admin.programs.edit', $item->id),
                'created_at' => $item->created_at,
            ]);
    }

    private function searchTeamMembers(string $query): Collection
    {
        return TeamMember::where('name', 'like', "%{$query}%")
            ->orWhere('position', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'title' => $item->name . ' - ' . $item->position,
                'type' => 'Team Member',
                'icon' => 'user',
                'url' => route('admin.team-members.edit', $item->id),
                'created_at' => $item->created_at,
            ]);
    }

    private function searchCommunities(string $query): Collection
    {
        return Community::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'title' => $item->name,
                'type' => 'Community',
                'icon' => 'users',
                'url' => route('admin.communities.edit', $item->id),
                'created_at' => $item->created_at,
            ]);
    }

    private function searchGallery(string $query): Collection
    {
        return Gallery::where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'title' => $item->title,
                'type' => 'Gallery',
                'icon' => 'images',
                'url' => route('admin.gallery.edit', $item->id),
                'created_at' => $item->created_at,
            ]);
    }

    private function searchTestimonials(string $query): Collection
    {
        return Testimonial::where('author_name', 'like', "%{$query}%")
            ->orWhere('text', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'title' => '"' . substr($item->text, 0, 50) . '..." - ' . $item->author_name,
                'type' => 'Testimonial',
                'icon' => 'quote-left',
                'url' => route('admin.testimonials.edit', $item->id),
                'created_at' => $item->created_at,
            ]);
    }

    private function searchHeroSlides(string $query): Collection
    {
        return HeroSlide::where('title', 'like', "%{$query}%")
            ->orWhere('subtitle', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'title' => $item->title,
                'type' => 'Hero Slide',
                'icon' => 'image',
                'url' => route('admin.hero-slides.edit', $item->id),
                'created_at' => $item->created_at,
            ]);
    }

    private function searchMessages(string $query): Collection
    {
        return ContactMessage::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('message', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'title' => $item->name . ' - ' . $item->email,
                'type' => 'Message',
                'icon' => 'envelope',
                'url' => route('admin.contact-messages.index'),
                'created_at' => $item->created_at,
            ]);
    }
}
