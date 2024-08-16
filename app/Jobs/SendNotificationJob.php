<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Services\FirebaseService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $deviceToken;
    protected $title;
    protected $body;
    protected $data;
    /**
     * Create a new job instance.
     */
    public function __construct($deviceToken, $title, $body, $data)
    {
        $this->deviceToken = $deviceToken;
        $this->title = $title;
        $this->body = $body;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(FirebaseService $firebaseService)
    {
        // Envoyer la notification

            $firebaseService->sendNotification($this->deviceToken, $this->title, $this->body, $this->data);
        
    }
}
