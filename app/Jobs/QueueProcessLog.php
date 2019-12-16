<?php

namespace App\Jobs;

use App\Enums\QueueEnum;
use App\Enums\ResponseCodeEnum;
use App\Enums\TimeConfigEnum;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\QueueProcessLog as QueueProcessLogModel;
use App\Models\QueueProcess;
use App\Libs\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QueueProcessLog implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $queue_id;
    private $status;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($queue_id, $status)
    {
        $this->queue_id = $queue_id;
        $this->status = $status;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            DB::beginTransaction();

            $snapshot = QueueProcess::selectedColumn()
                ->hospital()
                ->where('queue_process.id', $this->queue_id)
                ->first();

            if ($snapshot) {
                QueueProcessLogModel::create([
                    'queue_process_id'      => $this->queue_id,
                    'status'                => $this->status,
                    'is_valid'              => Helper::isQueueValid($this->status),
                    'time'                  => Carbon::now()->timeZone(TimeConfigEnum::zone),
                    'snapshot'              => json_encode($snapshot)
                ]);
            }

            DB::commit();
        } catch (\Error $error) {
            DB::rollBack();
        }
    }
}
