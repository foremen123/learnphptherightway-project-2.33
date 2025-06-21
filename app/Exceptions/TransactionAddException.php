<?php

namespace App\Exceptions;

class TransactionAddException extends \PDOException
{
    protected $message = "Transaction could not be added. Please try again later.";
}