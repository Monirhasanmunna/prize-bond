<?php

namespace App\Jobs;

use App\Http\Services\Feature\Notification\FcmService;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendFcmNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Notification $notification) {}

    /**
     * Execute the job.
     */
    public function handle(FcmService $fcm): void
    {
        $notification = $this->notification;

        $tokens = User::whereNotNull('fcm_token')->pluck('fcm_token')->toArray();

        if (empty($tokens)) return;

        $fcm->sendToMany($tokens, $notification->title, $notification->description, ['id' => $notification->id]);
        $notification->update(['is_sent' => true]);
    }
}
