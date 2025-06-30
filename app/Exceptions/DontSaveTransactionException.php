<?php

namespace App\Exceptions;

class DontSaveTransactionException extends \Exception
{
    protected $message = 'Some error occurred while saving the transaction.';
}