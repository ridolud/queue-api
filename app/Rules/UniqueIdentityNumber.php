<?php

namespace App\Rules;

use App\Models\Patient;
use Illuminate\Contracts\Validation\Rule;

class UniqueIdentityNumber implements Rule
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
        $patient = Patient::where('identity_number', $value)
            ->get()
            ->count();

        if ($patient > 0) {
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
        return 'This identity number already registered';
    }
}
