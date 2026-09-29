<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::orderBy('group')->orderBy('key')->get()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    public function edit(Setting $setting)
    {
        return view('admin.settings.edit', compact('setting'));
    }

    public function update(Request $request, Setting $setting)
    {
        $validated = $request->validate([
            'value' => 'required|string',
        ]);

        $setting->update($validated);

        return redirect()->route('admin.settings.index')
            ->with('success', 'Setting updated successfully.');
    }

    public function bulkUpdate(Request $request)
    {
        $settings = $request->input('settings', []);
        $files = $request->file('settings_files', []);

        // Update text values
        foreach ($settings as $id => $value) {
            Setting::where('id', $id)->update(['value' => $value]);
        }

        // Handle file uploads for identity/media-like settings
        foreach ($files as $id => $file) {
            if (!$file) { continue; }
            $setting = Setting::find($id);
            if (!$setting) { continue; }

            // Delete old file if it was previously stored
            if ($setting->value && str_starts_with($setting->value, 'settings/')) {
                Storage::disk('public')->delete($setting->value);
            }

            $path = $file->store('settings', 'public');
            $setting->update(['value' => $path]);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }
}
