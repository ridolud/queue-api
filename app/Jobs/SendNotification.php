<?php

namespace App\Jobs;

use App\Enums\ResponseCodeEnum;
use Edujugon\PushNotification\PushNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $devicetoken;
    private $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($devicetoken, $data)
    {
        //
        $this->devicetoken = $devicetoken;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $push = new PushNotification('apn');

        try {
            $push->setMessage($this->data)
                ->setDevicesToken([
                    $this->devicetoken,
                ]);

            $push = $push->send();
            $push = $push->getFeedback();

            Log::info($push);

        } catch (\Error $error) {
            Log::error($error);
        }
    }
}
