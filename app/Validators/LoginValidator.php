<?php

namespace App\Validators;

use Core\Request\Validation;

/**
 * Login Validator class
 *
 * @author Fil Beluan
 */
class LoginValidator extends Validation
{
    /**
     * Verify a valid email address
     *
     * @param string $email
     * @return void
     */
    public function email(string $email)
    {
        if (empty($email)) {
            return [
                'message' => "Empty email address",
                'input'   => $email
            ];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'message' => "Invalid email address",
                'input'   => $email
            ];
        }
    }

    /**
     * Verify password is not empty
     *
     * @param string $password
     * @return void
     */
    public function password(string $password)
    {
        if (empty($password)) {
            return [
                'message' => "Empty password",
                'input'   => ''
            ];
        }
    }
}
