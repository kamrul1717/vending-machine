<?php

namespace App\Exceptions;

use Exception;

class EmployeeNotFoundException extends PurchaseException
{
    public function __construct() {
        parent::__construct('Employee not found');
    }
}
