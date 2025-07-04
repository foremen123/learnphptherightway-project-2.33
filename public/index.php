<?php

declare(strict_types = 1);

use App\App;
use App\Config;
use App\Controllers\HomeController;
use App\Controllers\TransactionController;
use App\Router;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

const STORAGE_PATH = __DIR__ . '/../storage';
const VIEW_PATH = __DIR__ . '/../views';
const PATH_CVS = __DIR__ . '/../transactions_sample.csv';

$router = new Router();

$router
    ->get('/', [HomeController::class, 'index'])
    ->get('/create', [TransactionController::class, 'create'])
    ->post('/store', [TransactionController::class, 'store'])
    ->get('/transactions', [TransactionController::class, 'transactions']);

(new App(
    $router,
    ['uri' => $_SERVER['REQUEST_URI'], 'method' => $_SERVER['REQUEST_METHOD']],
    new Config($_ENV)
))->run();
