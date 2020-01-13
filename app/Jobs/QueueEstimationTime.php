<?php

namespace App\Jobs;

use App\Enums\QueueEnum;
use App\Enums\TimeConfigEnum;
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

            $queue_log = QueueProcess::where('doctor_schedule_id', $this->doctor_schedule_id)
                ->where('is_valid', QueueEnum::inValid)
                ->where('process_status', QueueEnum::checkOut)
//                ->where('submit_time', '<', Carbon::yesterday(TimeConfigEnum::zone))
                ->get();

            foreach ($queue_log as $queue) {
                $times = QueueProcessLog::where('queue_process_id', $queue->id)
                    ->whereIn('status', $status)
                    ->get()
                    ->pluck('time')
                    ->all();

                if (count($times) > 1) {
                    $difference = (int) Carbon::parse($times[0])->diffInMinutes($times[1]);
                    $total_patient++;
                    $total_time += $difference;
                }
            }

            $estimation = 15;

            if ($total_patient > 1) {
                $estimation = $total_time/$total_patient;
            }

            $now = Carbon::now()->timeZone(TimeConfigEnum::zone);

            QueueEstimationTimeModel::updateOrCreate(
                [
                    'doctor_schedule_id' => $this->doctor_schedule_id,
                ],
                [
                    'estimation' => round($estimation),
                    'time' => $now
                ]);

            DB::commit();
        } catch (\Error $error) {
            Log::info($error);
            DB::rollBack();
        }
    }
}
