<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoMetadata;
use Illuminate\Http\Request;

class SeoController extends Controller
{
    public function edit(string $type, int $id)
    {
        $modelClass = 'App\\Models\\' . ucfirst($type);
        
        if (!class_exists($modelClass)) {
            return back()->with('error', 'Invalid content type');
        }

        $model = $modelClass::findOrFail($id);
        $seo = SeoMetadata::forModel($modelClass, $id)->first() 
            ?? new SeoMetadata([
                'seoable_type' => $modelClass,
                'seoable_id' => $id,
            ]);

        return view('admin.seo.edit', compact('model', 'seo', 'type'));
    }

    public function update(Request $request, string $type, int $id)
    {
        $modelClass = 'App\\Models\\' . ucfirst($type);
        
        if (!class_exists($modelClass)) {
            return back()->with('error', 'Invalid content type');
        }

        $validated = $request->validate([
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'slug' => 'nullable|string|unique:seo_metadata,slug,' . ($seo->id ?? 'NULL'),
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'keywords' => 'nullable|string',
        ]);

        $seo = SeoMetadata::firstOrCreate([
            'seoable_type' => $modelClass,
            'seoable_id' => $id,
        ]);

        if ($request->hasFile('og_image')) {
            $validated['og_image'] = $request->file('og_image')
                ->store('seo/og-images', 'public');
        }

        $seo->update($validated + [
            'has_meta_title' => !empty($validated['meta_title']),
            'has_meta_description' => !empty($validated['meta_description']),
        ]);

        $seo->updateScore();

        return back()->with('success', 'SEO metadata updated successfully');
    }

    public function suggestions(string $type, int $id)
    {
        $modelClass = 'App\\Models\\' . ucfirst($type);
        $model = $modelClass::find($id);

        if (!$model) {
            return response()->json(['error' => 'Model not found'], 404);
        }

        $suggestions = [
            'title' => ucfirst($model->title ?? '') . ' | IEYDA Platform',
            'description' => substr(strip_tags($model->description ?? ''), 0, 160),
            'keywords' => $this->extractKeywords($model->description ?? ''),
        ];

        return response()->json($suggestions);
    }

    private function extractKeywords(string $text): string
    {
        $words = str_word_count($text, 1);
        $filtered = array_filter($words, function($word) {
            $common = ['the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for'];
            return strlen($word) > 3 && !in_array(strtolower($word), $common);
        });

        return implode(', ', array_slice(array_unique($filtered), 0, 5));
    }
}
