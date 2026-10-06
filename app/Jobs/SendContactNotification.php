<?php

namespace App\Jobs;

use App\Models\ContactMessage;
use App\Notifications\ContactMessageReceived;
use App\Services\PortfolioService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Throwable;

class SendContactNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 30;

    /** @var array<int, int> */
    public array $backoff = [10, 30, 60];

    public function __construct(public readonly ContactMessage $message) {}

    public function handle(PortfolioService $portfolio): void
    {
        $adminEmail = $portfolio->setting('email', config('mail.from.address'));

        if (blank($adminEmail)) {
            Log::warning('Contact notification skipped — no admin email configured.', [
                'contact_message_id' => $this->message->id,
            ]);

            return;
        }

        Notification::route('mail', $adminEmail)
            ->notify(new ContactMessageReceived($this->message));
    }

    public function failed(?Throwable $e): void
    {
        Log::error('Contact notification job failed.', [
            'contact_message_id' => $this->message->id,
            'error' => $e?->getMessage(),
        ]);
    }
}
