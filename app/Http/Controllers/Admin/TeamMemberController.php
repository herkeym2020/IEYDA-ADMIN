<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TeamMemberController extends Controller
{
    public function index()
    {
        // Show all team members (no pagination) so DataTables can display everything
        $teamMembers = TeamMember::orderBy('order')->get();
        return view('admin.team-members.index', compact('teamMembers'));
    }

    public function create()
    {
        return view('admin.team-members.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'type' => 'required|in:grand_patron,board_of_trustees,executive_present,executive_pioneering,staff,volunteer',
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'order' => 'nullable|integer|min:0',
            'priority' => 'nullable|integer|min:0|max:10',
            'is_active' => 'nullable|boolean',
            'social_links' => 'nullable|array',
            'location' => 'nullable|string|max:255',
            'achievements' => 'nullable|string',
        ];

        switch ($request->input('type')) {
            case 'grand_patron':
                $rules['salute'] = 'required|string|max:100';
                $rules['awards'] = 'nullable|string|max:255';
                $rules['position'] = 'required|string|max:255';
                $rules['bio'] = 'nullable|string';
                break;
            case 'board_of_trustees':
                $rules['position'] = 'required|string|max:255';
                $rules['bio'] = 'nullable|string';
                break;
            case 'executive_present':
            case 'executive_pioneering':
                $rules['position'] = 'required|string|max:255';
                $rules['executive_type'] = 'required|in:present,pioneering';
                $rules['term'] = 'nullable|string|max:100';
                $rules['department'] = 'nullable|string|max:100';
                $rules['bio'] = 'nullable|string';
                break;
            default:
                $rules['position'] = 'required|string|max:255';
                $rules['department'] = 'nullable|string|max:100';
                $rules['bio'] = 'nullable|string';
                break;
        }

        $rules['email'] = 'nullable|email|max:255';
        $rules['phone'] = 'nullable|string|max:50';

        $validated = $request->validate($rules);

        if (isset($validated['social_links']) && is_array($validated['social_links'])) {
            $validated['social_links'] = array_values(array_filter($validated['social_links'], function ($link) {
                return !empty($link['platform']) && !empty($link['url']);
            }));
        } else {
            $validated['social_links'] = [];
        }

        $validated['is_active'] = $request->has('is_active');

        if (isset($validated['achievements']) && !empty($validated['achievements'])) {
            $validated['achievements'] = array_map('trim', explode(',', $validated['achievements']));
        } else {
            $validated['achievements'] = [];
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('team', 'public');
        }

        if (in_array($validated['type'], ['executive_present', 'executive_pioneering'])) {
            $validated['executive_type'] = $validated['type'] === 'executive_present' ? 'present' : 'pioneering';
        }

        if (!isset($validated['order'])) {
            $validated['order'] = TeamMember::max('order') + 1;
        }

        TeamMember::create($validated);

        return redirect()->route('admin.team-members.index')
            ->with('success', 'Team member created successfully.');
    }

    public function edit(TeamMember $teamMember)
    {
        return view('admin.team-members.edit', compact('teamMember'));
    }

    public function show(TeamMember $teamMember)
    {
        // No dedicated show page; redirect to edit for convenience
        return redirect()->route('admin.team-members.edit', $teamMember);
    }

    public function update(Request $request, TeamMember $teamMember)
    {
        session()->flash('debug', 'Update method hit');

        $rules = [
            'type' => 'required|in:grand_patron,board_of_trustees,executive_present,executive_pioneering,staff,volunteer',
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'order' => 'nullable|integer|min:0',
            'priority' => 'nullable|integer|min:0|max:10',
            'is_active' => 'nullable|boolean',
            'social_links' => 'nullable|array',
            'location' => 'nullable|string|max:255',
            'achievements' => 'nullable|string',
        ];

        switch ($request->input('type')) {
            case 'grand_patron':
                $rules['salute'] = 'required|string|max:100';
                $rules['awards'] = 'nullable|string|max:255';
                $rules['position'] = 'required|string|max:255';
                $rules['bio'] = 'nullable|string';
                break;
            case 'board_of_trustees':
                $rules['position'] = 'required|string|max:255';
                $rules['bio'] = 'nullable|string';
                break;
            case 'executive_present':
            case 'executive_pioneering':
                $rules['position'] = 'required|string|max:255';
                $rules['executive_type'] = 'required|in:present,pioneering';
                $rules['term'] = 'nullable|string|max:100';
                $rules['department'] = 'nullable|string|max:100';
                $rules['bio'] = 'nullable|string';
                break;
            default:
                $rules['position'] = 'required|string|max:255';
                $rules['department'] = 'nullable|string|max:100';
                $rules['bio'] = 'nullable|string';
                break;
        }

        $rules['email'] = 'nullable|email|max:255';
        $rules['phone'] = 'nullable|string|max:50';

        $validated = $request->validate($rules);
        session()->flash('debug', 'Validation passed');

        if (isset($validated['social_links']) && is_array($validated['social_links'])) {
            $validated['social_links'] = array_values(array_filter($validated['social_links'], function ($link) {
                return !empty($link['platform']) && !empty($link['url']);
            }));
        } else {
            $validated['social_links'] = [];
        }

        $validated['is_active'] = $request->has('is_active');

        if (isset($validated['achievements']) && !empty($validated['achievements'])) {
            $validated['achievements'] = array_map('trim', explode(',', $validated['achievements']));
        } else {
            $validated['achievements'] = [];
        }

        if ($request->hasFile('image')) {
            if ($teamMember->image) {
                Storage::disk('public')->delete($teamMember->image);
            }
            $validated['image'] = $request->file('image')->store('team', 'public');
        }

        if (in_array($validated['type'], ['executive_present', 'executive_pioneering'])) {
            $validated['executive_type'] = $validated['type'] === 'executive_present' ? 'present' : 'pioneering';
        }

        $teamMember->update($validated);
        session()->flash('debug', 'Update executed');

        return redirect()->route('admin.team-members.index')
            ->with('success', 'Team member updated successfully.');
    }

    public function destroy(TeamMember $teamMember)
    {
        if ($teamMember->image) {
            Storage::disk('public')->delete($teamMember->image);
        }

        $teamMember->delete();

        return redirect()->route('admin.team-members.index')
            ->with('success', 'Team member deleted successfully.');
    }

    public function bulkUploadForm()
    {
        return view('admin.team-members.bulk-upload');
    }

    public function bulkUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $path = $request->file('file')->getRealPath();
        $rows = array_map('str_getcsv', file($path));
        if (empty($rows) || count($rows) === 1) {
            return back()->with('error', 'CSV file is empty or missing data rows.');
        }

        $headers = array_map(fn ($h) => strtolower(trim($h)), array_shift($rows));
        $missingHeaders = array_diff(['type', 'name', 'position'], $headers);
        if (!empty($missingHeaders)) {
            return back()->with('error', 'CSV missing required headers: ' . implode(', ', $missingHeaders));
        }

        $created = 0;
        $errors = [];
        $nextOrder = (int) TeamMember::max('order') + 1;
        $headerCount = count($headers);

        foreach ($rows as $index => $row) {
            if (count(array_filter($row, fn ($v) => trim((string) $v) !== '')) === 0) {
                continue;
            }

            $row = array_pad($row, $headerCount, null);
            $data = @array_combine($headers, $row);
            if ($data === false) {
                $errors[] = 'Row ' . ($index + 2) . ': column mismatch.';
                continue;
            }

            $payload = [
                'type' => $data['type'] ?? null,
                'name' => $data['name'] ?? null,
                'position' => $data['position'] ?? null,
                'executive_type' => $data['executive_type'] ?? null,
                'term' => $data['term'] ?? null,
                'department' => $data['department'] ?? null,
                'order' => isset($data['order']) && $data['order'] !== '' ? (int) $data['order'] : $nextOrder++,
                'priority' => isset($data['priority']) && $data['priority'] !== '' ? (int) $data['priority'] : null,
                'is_active' => isset($data['is_active']) ? filter_var($data['is_active'], FILTER_VALIDATE_BOOLEAN) : true,
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
                'location' => $data['location'] ?? null,
                'bio' => $data['bio'] ?? null,
                'achievements' => isset($data['achievements']) ? array_map('trim', explode('|', $data['achievements'])) : [],
                'awards' => $data['awards'] ?? null,
                'salute' => $data['salute'] ?? null,
                'image' => null,
                'social_links' => [],
            ];

            if (in_array($payload['type'], ['executive_present', 'executive_pioneering'])) {
                $payload['executive_type'] = $payload['type'] === 'executive_present' ? 'present' : 'pioneering';
            }

            if (!empty($data['image_url'])) {
                try {
                    $contents = @file_get_contents($data['image_url']);
                    if ($contents !== false) {
                        $ext = pathinfo(parse_url($data['image_url'], PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
                        $fileName = 'team/import_' . uniqid() . '.' . $ext;
                        Storage::disk('public')->put($fileName, $contents);
                        $payload['image'] = $fileName;
                    }
                } catch (\Throwable $e) {
                    // Ignore download errors
                }
            }

            $validator = Validator::make($payload, [
                'type' => 'required|in:grand_patron,board_of_trustees,executive_present,executive_pioneering,staff,volunteer',
                'name' => 'required|string|max:255',
                'position' => 'required|string|max:255',
                'executive_type' => 'nullable|in:present,pioneering',
                'term' => 'nullable|string|max:100',
                'department' => 'nullable|string|max:100',
                'order' => 'nullable|integer|min:0',
                'priority' => 'nullable|integer|min:0|max:10',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:50',
                'location' => 'nullable|string|max:255',
                'bio' => 'nullable|string',
                'awards' => 'nullable|string|max:255',
                'salute' => 'nullable|string|max:100',
            ]);

            if ($validator->fails()) {
                $errors[] = 'Row ' . ($index + 2) . ': ' . implode('; ', $validator->errors()->all());
                continue;
            }

            TeamMember::create($payload);
            $created++;
        }

        if (!empty($errors)) {
            return back()->with('warning', "Imported {$created} rows with some issues:")
                ->with('import_errors', $errors);
        }

        return redirect()->route('admin.team-members.index')
            ->with('success', "Imported {$created} team members successfully.");
    }

    public function bulkDelete(Request $request)
    {
        $ids = explode(',', $request->input('ids'));
        $members = TeamMember::whereIn('id', $ids)->get();
        foreach ($members as $member) {
            if ($member->image) {
                Storage::disk('public')->delete($member->image);
            }
            $member->delete();
        }
        return redirect()->route('admin.team-members.index')
            ->with('success', 'Selected team members deleted successfully.');
    }
}
