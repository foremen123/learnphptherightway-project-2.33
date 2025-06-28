<?php

namespace App\Exceptions\Container;

class NotFoundException extends \Exception Implements \Psr\Container\NotFoundExceptionInterface
{
}