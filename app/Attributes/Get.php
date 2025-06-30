<?php

declare(strict_types=1);

namespace App\Attributes;

use Attribute;
use App\Attributes\Route;

#[Attribute]

class Get extends Route
{
    public function __construct(string $pathRoute)
    {
        parent::__construct($pathRoute, 'get');
    }
}