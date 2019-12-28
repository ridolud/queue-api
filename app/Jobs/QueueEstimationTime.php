<?php

namespace App\Jobs;

use App\Enums\NotificationTypeEnum;
use App\Enums\QueueEnum;
use App\Enums\TimeConfigEnum;
use App\Libs\Helper;
use App\Models\QueueProcess;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\QueueProcessLog;
use App\Models\QueueEstimationTime as QueueEstimationTimeModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QueueEstimationTime implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $current_queue;
    private $doctor_schedule_id;
    private $submit_time;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($doctor_schedule_id, $submit_time)
    {
        $this->doctor_schedule_id = $doctor_schedule_id;
        $this->submit_time = $submit_time;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $status = [
            QueueEnum::checkIn,
            QueueEnum::checkOut,
        ];

        $total_patient = 0;
        $total_time = 0;

        try {
            DB::beginTransaction();

            $queues = QueueProcess::where('doctor_schedule_id', $this->doctor_schedule_id)
                ->where('is_valid', QueueEnum::Valid)
                ->where('process_status', QueueEnum::waiting)
                ->where('submit_time', '<', $this->submit_time)
                ->get();

            foreach ($queues as $queue) {
                $times = $queue->log()
                    ->whereIn('status', $status)
                    ->get()
                    ->pluck('time')
                    ->all();

                if (count($times) == 2) {
                    $difference = (int) Carbon::parse($times[0])->diffInMinutes($times[1]);
                    $total_patient++;
                    $total_time += $difference;
                }
            }

            $estimation = (int) ($total_time/$total_patient);

            $now = Carbon::now()->timeZone(TimeConfigEnum::zone);

            QueueEstimationTimeModel::updateOrCreate(
                ['doctor_schedule_id' => $this->doctor_schedule_id],
                [
                    'estimation' => $estimation,
                    'time' => $now
                ]);

            SendNotification::dispatch()
                ->delay(now()->addSeconds(15))
                ->onQueue('send-notification');

            DB::commit();
        } catch (\Error $error) {
            Log::info($error);
            DB::rollBack();
        }
    }
}
