<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Rules\Password;

/**
 * REMOVE ME IF MAKING CHANGES TO THIS FILE
 *
 * This file is excluded from coverage as it is vendor-published code
 *
 * @codeCoverageIgnore
 */
trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array
     */
    protected function passwordRules()
    {
        return ['required', 'string', new Password, 'confirmed'];
    }
}
