<?php

namespace App\Exceptions;

use Exception;

class InvalidCardException extends PurchaseException
{
    public function __construct() {
        parent::__construct('Invalid card');
    }
}
