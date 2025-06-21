<?php

namespace App\Exceptions;

class TransactionGetException extends \PDOException
{
    protected $message = "Transaction Not Found";
}