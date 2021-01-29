<?php

namespace App\Exceptions;

use Exception;

/**
 * ValidationException
 *
 * @uses Exception
 */
class ValidationException extends Exception
{
    /**
     * constructor
     *
     * @return void
     */
    public function __construct($error)
    {
        parent::__construct();

        $this->error = $error;
    }
}