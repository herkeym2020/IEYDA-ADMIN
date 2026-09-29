<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GuestRegistration;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GuestRegistrationController extends Controller
{
    public function index(Request $request)
    {
        $guests = $this->buildQuery($request)->latest()->paginate(15)->withQueryString();
        $settings = [
            'limit' => (int) Setting::get('guest_registration_limit', '100'),
            'device_restriction_enabled' => filter_var(Setting::get('guest_registration_device_restriction_enabled', '1'), FILTER_VALIDATE_BOOLEAN),
        ];

        return view('admin.guest-registrations.index', compact('guests', 'settings'));
    }

    public function show($id)
    {
        $guest = GuestRegistration::findOrFail($id);
        return view('admin.guest-registrations.show', compact('guest'));
    }

    public function updateStatus(Request $request, $id)
    {
        $guest = GuestRegistration::findOrFail($id);
        $guest->update(['status' => $request->status]);
        return back()->with('success', 'Status updated to ' . $request->status);
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'registration_limit' => ['required', 'integer', 'min:0'],
            'device_restriction_enabled' => ['nullable', 'in:0,1'],
        ]);

        Setting::set('guest_registration_limit', (string) $validated['registration_limit'], 'text', 'guest_registration');
        Setting::set('guest_registration_device_restriction_enabled', (string) ($validated['device_restriction_enabled'] ?? 0), 'text', 'guest_registration');

        return back()->with('success', 'Guest registration settings updated successfully.');
    }

    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'guest_ids' => ['required', 'array'],
            'guest_ids.*' => ['exists:guest_registrations,id'],
            'bulk_action' => ['required', 'in:update,delete'],
        ]);

        $guestIds = $validated['guest_ids'];

        if ($validated['bulk_action'] === 'delete') {
            $guests = GuestRegistration::whereIn('id', $guestIds)->get();
            foreach ($guests as $guest) {
                if ($guest->pdf_path) {
                    Storage::disk('public')->delete($guest->pdf_path);
                }
            }

            GuestRegistration::whereIn('id', $guestIds)->delete();
            return back()->with('success', count($guestIds) . ' registrations deleted successfully.');
        }

        $request->validate(['status' => ['required', 'in:pending,confirmed,attended,cancelled']]);
        GuestRegistration::whereIn('id', $guestIds)->update(['status' => $request->status]);

        return back()->with('success', count($guestIds) . ' registrations updated successfully.');
    }

    public function exportCsv(Request $request)
    {
        $guests = $this->buildQuery($request)->latest()->get();
        $filename = 'guest-registrations-' . now()->format('YmdHis') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->stream(function () use ($guests) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Registration Number', 'Full Name', 'Email', 'Phone', 'Status', 'Registered At']);

            foreach ($guests as $guest) {
                fputcsv($handle, [
                    $guest->registration_number,
                    $guest->full_name,
                    $guest->email ?? '—',
                    $guest->phone ?? '—',
                    $guest->status,
                    $guest->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $guests = $this->buildQuery($request)->latest()->get();
        $pdf = Pdf::loadView('admin.guest-registrations.export-pdf', [
            'guests' => $guests,
            'generatedAt' => now()->format('d M Y, h:i A'),
        ]);

        return $pdf->download('guest-registrations-' . now()->format('YmdHis') . '.pdf');
    }

    public function destroy($id)
    {
        $guest = GuestRegistration::findOrFail($id);
        if ($guest->pdf_path) {
            Storage::disk('public')->delete($guest->pdf_path);
        }
        $guest->delete();
        return redirect()->route('admin.guest-registrations.index')->with('success', 'Registration deleted successfully.');
    }

    public function destroyAll()
    {
        GuestRegistration::truncate();
        Storage::disk('public')->deleteDirectory('guest-invitations');
        return redirect()->route('admin.guest-registrations.index')->with('success', 'All registrations cleared.');
    }

    private function buildQuery(Request $request)
    {
        $query = GuestRegistration::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('registration_number', 'like', "%{$search}%");
            });
        }

        return $query;
    }
}