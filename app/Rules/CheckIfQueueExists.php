<?php

namespace App\Rules;

use App\Models\QueueProcess;
use Illuminate\Contracts\Validation\Rule;

class CheckIfQueueExists implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        //
        $is_queue_exists = QueueProcess::where('is_valid', 1)->where('patient_id', $value)->get()->count();

        if ($is_queue_exists) {
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
        return 'The validation error message.';
    }
}
