<?php

namespace App\Console\Commands;

use App\Jobs\SendBulkSmsJob;
use Illuminate\Console\Command;

class SendBulkSmsCommand extends Command
{
    protected $signature = 'sms:send {numbers* : One or more recipient phone numbers} {--body= : Message text to send}';
    protected $description = 'Send a bulk SMS to provided phone numbers using the configured provider';

    public function handle(): int
    {
        $numbers = (array) $this->argument('numbers');
        $body = (string) ($this->option('body') ?? 'Hello from IEYDA CMS');

        if (empty($numbers)) {
            $this->error('No recipient numbers provided');
            return self::FAILURE;
        }

        SendBulkSmsJob::dispatch($numbers, $body);
        $this->info('Bulk SMS dispatched to queue. Provider: ' . config('sms.provider'));

        return self::SUCCESS;
    }
}
