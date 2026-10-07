<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MonthlyRealization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MonthlyRealizationController extends Controller
{
    public function index()
    {
        return view('admin.monthly-realizations.index', ['realizations' => MonthlyRealization::orderByDesc('month')->paginate(20)]);
    }

    public function create()
    {
        return view('admin.monthly-realizations.form', ['realization' => new MonthlyRealization()]);
    }

    public function store(Request $request)
    {
        MonthlyRealization::create($this->validated($request));
        return redirect()->route('admin.monthly-realizations.index')->with('success', 'Monthly realization created.');
    }

    public function edit(MonthlyRealization $monthly_realization)
    {
        return view('admin.monthly-realizations.form', ['realization' => $monthly_realization]);
    }

    public function update(Request $request, MonthlyRealization $monthly_realization)
    {
        $monthly_realization->update($this->validated($request, $monthly_realization));
        return redirect()->route('admin.monthly-realizations.index')->with('success', 'Monthly realization updated.');
    }

    public function destroy(MonthlyRealization $monthly_realization)
    {
        if ($monthly_realization->image) Storage::disk('public')->delete($monthly_realization->image);
        $monthly_realization->delete();
        return redirect()->route('admin.monthly-realizations.index')->with('success', 'Monthly realization deleted.');
    }

    private function validated(Request $request, ?MonthlyRealization $existing = null): array
    {
        $data = $request->validate([
            'month' => 'required|date',
            'title' => 'required|string|max:255',
            'community_name' => 'required|string|max:255',
            'lga' => 'nullable|string|max:100',
            'summary' => 'required|string|max:1000',
            'details' => 'nullable|string',
            'impact_metric' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'priority' => 'nullable|integer|min:0|max:999',
        ]);
        if ($request->hasFile('image')) {
            if ($existing?->image) Storage::disk('public')->delete($existing->image);
            $data['image'] = $request->file('image')->store('monthly-realizations', 'public');
        }
        $data['is_active'] = $request->boolean('is_active');
        return $data;
    }
}
