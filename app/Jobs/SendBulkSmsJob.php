<?php

namespace App\Jobs;

use App\Services\BulkSmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class SendBulkSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var array */
    protected $recipients;

    /** @var string */
    protected $body;

    public function __construct(array $recipients, string $body)
    {
        $this->recipients = $recipients;
        $this->body = $body;
    }

    public function handle(): void
    {
        foreach ($this->recipients as $to) {
            $result = BulkSmsService::sendSingle($to, $this->body);

            DB::table('sms_messages')->insert([
                'phone' => $to,
                'body' => $this->body,
                'provider' => $result['provider'] ?? null,
                'status' => ($result['success'] ?? false) ? 'sent' : 'failed',
                'provider_message_id' => $result['provider_message_id'] ?? null,
                'error' => $result['error'] ?? null,
                'sent_at' => ($result['success'] ?? false) ? now() : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
