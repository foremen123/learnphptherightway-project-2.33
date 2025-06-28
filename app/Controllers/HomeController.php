<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\App;
use App\Container;
use App\Services\InvoiceService;
use App\View;

class HomeController
{

    public function __construct(private InvoiceService $invoiceService)
    {
    }

    public function index(): View
    {
        $this->invoiceService->process([], 24);


        return View::make('index');
    }

    public function generator(): void
    {
        $numbers = $this->lazyRange(1, 100);

        foreach ($numbers as $key => $number) {
            echo $key . ' - ' . $number . '<br/>';
        }
    }

    public function lazyRange(int $start, int $end): \Generator
    {
        for ($i = $start; $i <= $end; $i++) {
            yield $i * 5 => $i;
        }
    }
}
