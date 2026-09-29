<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactMessage;
use App\Models\MessageReply;
use App\Services\AutoReplyService;

class ContactMessageController extends Controller
{
    public function export(Request $request)
    {
        $query = ContactMessage::query();
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('subject', 'like', "%$search%")
                  ->orWhere('message', 'like', "%$search%")
                  ->orWhere('category', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%")
                  ->orWhere('ip_address', 'like', "%$search%")
                  ->orWhere('user_agent', 'like', "%$search%")
                  ->orWhere('status', 'like', "%$search%") ;
            });
        }
        $messages = $query->orderBy('created_at', 'desc')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="contact_messages.csv"',
        ];

        $callback = function() use ($messages) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'ID', 'Name', 'Email', 'Phone', 'Category', 'Subject', 'Message', 'IP Address', 'User Agent', 'Status', 'Created At'
            ]);
            foreach ($messages as $msg) {
                fputcsv($handle, [
                    $msg->id,
                    $msg->name,
                    $msg->email,
                    $msg->phone,
                    $msg->category,
                    $msg->subject,
                    $msg->message,
                    $msg->ip_address,
                    $msg->user_agent,
                    $msg->status,
                    $msg->created_at,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
    
    public function index(Request $request)
    {
        $query = ContactMessage::query();
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('subject', 'like', "%$search%")
                  ->orWhere('message', 'like', "%$search%")
                  ->orWhere('category', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%")
                  ->orWhere('ip_address', 'like', "%$search%")
                  ->orWhere('user_agent', 'like', "%$search%")
                  ->orWhere('status', 'like', "%$search%") ;
            });
        }
        $messages = $query->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.contact_messages.index', compact('messages'));
    }

    public function show(ContactMessage $contact_message)
    {
        // Mark as read
        $contact_message->update(['status' => 'read']);
        
        // Load all replies with proper ordering
        $contact_message->load('replies');
        
        return view('admin.contact_messages.show', compact('contact_message'));
    }

    public function reply(Request $request, ContactMessage $contact_message)
    {
        $request->validate([
            'reply_text' => 'required|string|min:5',
        ]);

        // Send manual reply via service
        AutoReplyService::sendManualReply($contact_message, $request->reply_text);

        return redirect()->route('admin.contact-messages.show', $contact_message)
            ->with('success', 'Reply sent to ' . $contact_message->email);
    }

    public function destroy(ContactMessage $contact_message)
    {
        $contact_message->delete();
        return redirect()->route('admin.contact-messages.index')->with('success', 'Message deleted.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        ContactMessage::whereIn('id', $ids)->delete();
        return redirect()->route('admin.contact-messages.index')->with('success', 'Selected messages deleted.');
    }
}
