<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\Attributes\Route;
use App\View;
use App\Attributes\Get;

class HomeController
{
    #[Route('/')]
    public function index(): View
    {
        return View::make('index');
    }

}