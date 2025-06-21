<?php

declare(strict_types=1);

namespace App\Controllers;

session_start();

use App\Models\TransactionsModel;
use App\Transaction;
use App\TransactionsCalculate;
use App\View;

class TransactionController
{
    // здесь будем все вызывать, проверка поста, и файла создание переменной с файлом, запись в бд, и вывод остального уже от рефакторенного
    public function transactions(): View
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $transactions = $_SESSION['transactions']?? [];
        $total = $_SESSION['total']?? ['income' => 0, 'expense' => 0, 'net' => 0];

        unset($_SESSION['transactions'], $_SESSION['total']);

        return View::make('transaction/transactions',
            [
                'transactions' => $transactions ,
                'total' => $total
            ]);
    }

    public function store(): void
    {
        $fileName = $_FILES['upload']['tmp_name'];
        $transactionService = new Transaction();
        $transactionModel = new TransactionsModel();

        $transactions = $transactionService->openFile($fileName, [$transactionService, 'extractTransaction']);

        $transactionModel->addTransactions($transactions);
        $transactionsFormatted = $transactionModel->getAll();

        $total = TransactionsCalculate::totalCalculate($transactions);
        $total = TransactionsCalculate::totalFormatted($total);

        $_SESSION['transactions'] = $transactionsFormatted;
        $_SESSION['total'] = $total;

        header('Location: /transactions');
        exit;
    }

    public function create(): View
    {
        return View::make('transaction/create');
    }
}