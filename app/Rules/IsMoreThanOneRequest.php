<?php

namespace App\Rules;

use App\Enums\QueueEnum;
use App\Models\QueueProcess;
use Illuminate\Contracts\Validation\Rule;

class IsMoreThanOneRequest implements Rule
{

    private $queue_id;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($queue_id)
    {
        $this->queue_id = $queue_id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $queue = QueueProcess::find($this->queue_id);
        if ($value == $queue->process_status) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Process status already changes';
    }
}
