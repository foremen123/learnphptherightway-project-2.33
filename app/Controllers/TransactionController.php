<?php

namespace App\Controllers;

use App\CalculateTransaction;
use App\Exceptions\TransactionAddException;
use App\Helper;
use App\Models\TransactionModel;
use App\Transaction;
use App\View;

class TransactionController
{
    private $transaction;
    private $transactionModel;

    public function __construct()
    {
        $this->transaction = new Transaction();
        $this->transactionModel = new TransactionModel();
    }

    public function transactions()
    {
        try {
            if (!isset($_FILES['upload']) || $_FILES['upload']['error'] !== UPLOAD_ERR_OK) {
                http_response_code(400);
                return View::make('error/404', ['message' => 'Ошибка загрузки файла']);
            }

            $transactions = $this->transaction->getTransactions(
                $_FILES['upload']['tmp_name'],
                [$this->transaction, 'extractTransaction']
            );

            if (empty($transactions)) {
                http_response_code(404);
                return View::make('error/404', ['message' => 'Не найдено ни одной транзакции']);
            }

            $this->transactionModel->addTransactions($transactions);

            $dbTransactions = $this->transactionModel->getAllTransactions();

            $total = CalculateTransaction::calculateTotal($dbTransactions);
            $formattedTotal = CalculateTransaction::formatTotal($total);

            return View::make(
                'transaction/transactions',
                ['transactions' => $dbTransactions, 'total' => $formattedTotal]
            );

        } catch (\Throwable $e) {
            http_response_code(500);
            return View::make('error/500', ['message' => $e->getMessage() . ' - ' . $e->getFile() . ':' . $e->getLine()]);
        }
    }

    public function create(): View
    {
        return View::make('transaction/create');
    }
}