<?php


namespace App\Models;


use App\Libs\UUIDGenerator;
use Illuminate\Database\Eloquent\Model;

class QueueProcessLog extends Model
{
    use UUIDGenerator;

    protected $table = "queue_process_log";
    protected $fillable = [
        'queue_process_id',
        'status',
        'is_valid',
        'time',
        'snapshot'
    ];
    public $incrementing = false;
    public $timestamps = false;

    public function queue()
    {
        return $this->belongsTo(QueueProcess::class, 'queue_process_id');
    }

}
