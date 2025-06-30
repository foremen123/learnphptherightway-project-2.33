<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\Attributes\Get;
use App\Attributes\Post;
use App\Exceptions\NotLoadFileException;
use App\Models\TransactionModel;
use App\Transactions\Transaction;
use App\Transactions\TransactionCalculate;
use App\View;

session_start();

class TransactionController
{

    #[Get('/create')]

    public function index(): View
    {
        return View::make('transaction/create');
    }

    /**
     * @throws NotLoadFileException
     * @throws \Exception
     */

    #[Post('/store')]

    public function store(): void
    {
        $file = $_FILES['upload']['tmp_name'] ?? null;
        if (is_null($file)) throw new NotLoadFileException();

        $transactionService = new Transaction();
        $transactionModel = new TransactionModel();

        $transactions = $transactionService->readCsv($file);
        if (empty($transactions)) {
            throw new \Exception('No transactions found in the file.');
        }

        $formattedTransactionDB = $transactionService->formatTransactionDB($transactions);
        $transactionModel->saveTodDataBase($formattedTransactionDB);

        $transactions = $transactionModel->getAll();
        $formattedTransactionView = $transactionService->formatTransactionView($transactions);

        $totalAmount = TransactionCalculate::calculate($transactions);
        $formattedTotalAmount = $transactionService->formatTotal($totalAmount);

        $_SESSION['totalAmount'] = $formattedTotalAmount;
        $_SESSION['transactions'] = $formattedTransactionView;

        header('Location: /transactions');
        exit;
    }

    #[Get('/transactions')]

    public function transactions(): View
    {
        $transaction = $_SESSION['transactions'] ?? [];
        $total = $_SESSION['totalAmount'] ??
            [
                'income' => 0,
                'expense' => 0,
                'net' => 0
            ];

        return View::make('transaction/transactions',
            [
                'transactions' => $transaction,
                'total' => $total
            ]);
    }
}