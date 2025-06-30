<?php

namespace App\Exceptions;

class NotLoadFileException extends \Exception
{
    protected $message = 'File not found(';
}