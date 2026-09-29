@extends('admin.layouts.app')

@section('content')
<style>
    .chat-container {
        background: #f9fafb;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        max-height: 600px;
        overflow-y: auto;
    }

    .message-bubble {
        margin-bottom: 15px;
        display: flex;
        gap: 10px;
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .message-bubble.customer {
        justify-content: flex-start;
    }

    .message-bubble.admin {
        justify-content: flex-end;
    }

    .bubble-content {
        max-width: 70%;
        padding: 12px 15px;
        border-radius: 12px;
        word-wrap: break-word;
    }

    .message-bubble.customer .bubble-content {
        background: white;
        border: 1px solid #e5e7eb;
        color: #1f2937;
        border-bottom-left-radius: 4px;
    }

    .message-bubble.admin .bubble-content {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-bottom-right-radius: 4px;
    }

    .message-meta {
        font-size: 12px;
        color: #9ca3af;
        margin-top: 4px;
        text-align: center;
    }

    .message-bubble.admin .message-meta {
        text-align: right;
    }

    .message-bubble.customer .message-meta {
        text-align: left;
    }

    .auto-reply-badge {
        display: inline-block;
        background: #dbeafe;
        color: #0c4a6e;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        margin-left: 5px;
    }

    .reply-form {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
    }

    .reply-textarea {
        width: 100%;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 12px;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        font-size: 14px;
        resize: vertical;
        min-height: 120px;
        transition: border-color 0.3s;
    }

    .reply-textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
        margin-bottom: 20px;
    }

    .info-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 15px;
    }

    .info-label {
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .info-value {
        font-size: 14px;
        color: #1f2937;
        font-weight: 500;
    }

    .badge-category {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-category.general { background: #dbeafe; color: #0c4a6e; }
    .badge-category.membership { background: #dcfce7; color: #15803d; }
    .badge-category.volunteer { background: #fce7f3; color: #be185d; }
    .badge-category.events { background: #fed7aa; color: #92400e; }
    .badge-category.programs { background: #f3e8ff; color: #6b21a8; }
    .badge-category.partnership { background: #e0e7ff; color: #3730a3; }

    .status-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-badge.new { background: #fecaca; color: #991b1b; }
    .status-badge.read { background: #bfdbfe; color: #1e40af; }
    .status-badge.replied { background: #bbf7d0; color: #065f46; }

    .header-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px;
        border-radius: 12px;
        margin-bottom: 25px;
    }

    .header-section h1 {
        margin: 0 0 10px 0;
        font-size: 24px;
    }

    .header-section .meta {
        font-size: 14px;
        opacity: 0.9;
        margin: 5px 0;
    }

    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: white;
        font-size: 16px;
    }

    .avatar.customer {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .avatar.admin {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .btn-group {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }
</style>

<div class="container py-4">
    <!-- Header Section -->
    <div class="header-section">
        <h1>{{ $contact_message->subject }}</h1>
        <div class="meta">
            From: <strong>{{ $contact_message->name }}</strong> ({{ $contact_message->email }})
        </div>
        <div class="meta">
            <span class="badge-category {{ $contact_message->category }}">{{ ucfirst($contact_message->category) }}</span>
            <span class="status-badge {{ $contact_message->status }}">{{ ucfirst($contact_message->status) }}</span>
        </div>
    </div>

    <!-- Info Cards -->
    <div class="info-grid">
        <div class="info-card">
            <div class="info-label">Sender Name</div>
            <div class="info-value">{{ $contact_message->name }}</div>
        </div>
        <div class="info-card">
            <div class="info-label">Email Address</div>
            <div class="info-value">{{ $contact_message->email }}</div>
        </div>
        <div class="info-card">
            <div class="info-label">Phone Number</div>
            <div class="info-value">{{ $contact_message->phone ?? 'Not provided' }}</div>
        </div>
        <div class="info-card">
            <div class="info-label">Received At</div>
            <div class="info-value">{{ $contact_message->created_at->format('M d, Y H:i') }}</div>
        </div>
        <div class="info-card">
            <div class="info-label">IP Address</div>
            <div class="info-value" style="font-family: monospace; font-size: 12px;">{{ $contact_message->ip_address }}</div>
        </div>
        <div class="info-card">
            <div class="info-label">Replies Count</div>
            <div class="info-value">{{ $contact_message->replies->count() }} reply(ies)</div>
        </div>
    </div>

    <!-- Chat Container -->
    <div class="chat-container">
        <!-- Original Message -->
        <div class="message-bubble customer">
            <div class="avatar customer">{{ substr($contact_message->name, 0, 1) }}</div>
            <div>
                <div class="bubble-content">
                    {{ $contact_message->message }}
                </div>
                <div class="message-meta">
                    {{ $contact_message->created_at->format('M d, Y H:i') }}
                </div>
            </div>
        </div>

        <!-- Replies -->
        @forelse ($contact_message->replies as $reply)
            <div class="message-bubble admin">
                <div>
                    <div class="bubble-content">
                        {{ $reply->reply_text }}
                        @if ($reply->reply_from === 'auto')
                            <span class="auto-reply-badge">Auto Reply</span>
                        @endif
                    </div>
                    <div class="message-meta">
                        {{ $reply->created_at->format('M d, Y H:i') }}
                        @if ($reply->sent_to_user)
                            <span style="color: #10b981;">✓ Sent to user</span>
                        @else
                            <span style="color: #f59e0b;">⚠ Pending send</span>
                        @endif
                    </div>
                </div>
                <div class="avatar admin">A</div>
            </div>
        @empty
            <div style="text-align: center; padding: 40px; color: #9ca3af;">
                <p style="margin: 0;">No replies yet. Send the first reply below.</p>
            </div>
        @endforelse
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div style="background: #dcfce7; color: #15803d; padding: 12px 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #10b981;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Reply Form -->
    <div class="reply-form">
        <h3 style="margin-top: 0; color: #1f2937;">Send a Reply</h3>
        <form action="{{ route('admin.contact-messages.reply', $contact_message) }}" method="POST">
            @csrf
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                    Your Reply *
                </label>
                <textarea 
                    name="reply_text" 
                    class="reply-textarea"
                    placeholder="Type your response here..."
                    required
                ></textarea>
                @error('reply_text')
                    <p style="color: #dc2626; font-size: 12px; margin-top: 5px;">{{ $message }}</p>
                @enderror
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-secondary">Back to List</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Send Reply
                </button>
            </div>
        </form>
    </div>

    <!-- Additional Info -->
    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
        <details style="cursor: pointer;">
            <summary style="font-weight: 600; color: #374151; padding: 10px; background: #f9fafb; border-radius: 6px;">
                Show Technical Details
            </summary>
            <div style="margin-top: 15px; padding: 15px; background: #1f2937; color: #d1d5db; border-radius: 6px; font-family: monospace; font-size: 12px; line-height: 1.6; overflow-x: auto;">
                <div><strong>User Agent:</strong><br>{{ $contact_message->user_agent }}</div>
            </div>
        </details>
    </div>
</div>

<style>
    .container {
        max-width: 900px;
        margin: 0 auto;
    }
</style>
@endsection

