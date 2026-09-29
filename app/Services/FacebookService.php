<?php

namespace App\Services;

use App\Models\FacebookPost;
use App\Models\News;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FacebookService
{
    private $pageAccessToken;
    private $pageId;
    private $verifyToken;
    private $appSecret;

    public function __construct()
    {
        $this->pageAccessToken = config('services.facebook.page_access_token');
        $this->pageId = config('services.facebook.page_id');
        $this->verifyToken = config('services.facebook.verify_token');
        $this->appSecret = config('services.facebook.app_secret');
    }

    /**
     * Verify webhook signature from Facebook
     */
    public function verifyWebhookSignature(string $payload, string $signature): bool
    {
        if (!$this->appSecret) {
            Log::warning('Facebook app secret not configured');
            return false;
        }

        $expectedSignature = 'sha256=' . hash_hmac('sha256', $payload, $this->appSecret);
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Process webhook data from Facebook
     */
    public function processWebhook(array $data): void
    {
        // Facebook sends data in 'entry' array
        if (!isset($data['entry'])) {
            return;
        }

        foreach ($data['entry'] as $entry) {
            if (isset($entry['changes'])) {
                foreach ($entry['changes'] as $change) {
                    if ($change['field'] === 'feed') {
                        $this->handleFeedChange($change['value']);
                    }
                }
            }
        }
    }

    /**
     * Handle feed change (new post)
     */
    private function handleFeedChange(array $postData): void
    {
        $facebookPostId = $postData['post_id'] ?? null;

        if (!$facebookPostId) {
            return;
        }

        // Check if already imported
        if (FacebookPost::where('facebook_post_id', $facebookPostId)->exists()) {
            Log::info("Facebook post already imported: $facebookPostId");
            return;
        }

        try {
            // Fetch full post details from Facebook API
            $fullPost = $this->fetchPostDetails($facebookPostId);

            if (!$fullPost) {
                Log::warning("Could not fetch post details for: $facebookPostId");
                return;
            }

            // Create news post
            $newsPost = News::create([
                'title' => $this->extractTitle($fullPost),
                'content' => $fullPost['message'] ?? $fullPost['story'] ?? '',
                'image' => $fullPost['full_picture'] ?? null,
                'is_published' => true,
                'published_at' => now(),
            ]);

            // Track Facebook post
            FacebookPost::create([
                'facebook_post_id' => $facebookPostId,
                'facebook_page_id' => $fullPost['id'] ?? $this->pageId,
                'message' => $fullPost['message'] ?? null,
                'story' => $fullPost['story'] ?? null,
                'full_picture' => $fullPost['full_picture'] ?? null,
                'link' => $fullPost['link'] ?? null,
                'post_url' => "https://facebook.com/{$facebookPostId}",
                'created_time' => $fullPost['created_time'] ?? now(),
                'imported_at' => now(),
                'news_id' => $newsPost->id,
                'raw_data' => $fullPost,
            ]);

            Log::info("Created news post from Facebook: $facebookPostId -> News ID: {$newsPost->id}");
        } catch (\Exception $e) {
            Log::error("Error processing Facebook post: {$e->getMessage()}");
        }
    }

    /**
     * Fetch post details from Facebook Graph API
     */
    private function fetchPostDetails(string $postId): ?array
    {
        try {
            $response = Http::get("https://graph.facebook.com/v18.0/{$postId}", [
                'fields' => 'id,message,story,full_picture,link,created_time,type,permalink_url',
                'access_token' => $this->pageAccessToken,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error("Facebook API error: {$response->body()}");
            return null;
        } catch (\Exception $e) {
            Log::error("Facebook API request failed: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Extract title from post data
     */
    private function extractTitle(array $postData): string
    {
        // Use first 100 chars of message as title
        $text = $postData['message'] ?? $postData['story'] ?? 'Facebook Post';
        $title = substr(strip_tags($text), 0, 100);

        return $title ?: 'Facebook Post';
    }

    /**
     * Get page posts from Facebook API (for manual sync)
     */
    public function syncPagePosts(int $limit = 10): int
    {
        try {
            $response = Http::get("https://graph.facebook.com/v18.0/{$this->pageId}/feed", [
                'fields' => 'id,message,story,full_picture,link,created_time,type',
                'limit' => $limit,
                'access_token' => $this->pageAccessToken,
            ]);

            if (!$response->successful()) {
                Log::error("Facebook sync failed: {$response->body()}");
                return 0;
            }

            $count = 0;
            foreach ($response->json('data', []) as $post) {
                if (!FacebookPost::where('facebook_post_id', $post['id'])->exists()) {
                    $this->handleFeedChange(['post_id' => $post['id']]);
                    $count++;
                }
            }

            return $count;
        } catch (\Exception $e) {
            Log::error("Facebook sync error: {$e->getMessage()}");
            return 0;
        }
    }
}
