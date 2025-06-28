<?php

declare(strict_types=1);

namespace App\TransactionHelper;

use App\View;

class Transaction
{
    // Здесь должно быть открытие файла, и поиск пути к файлу транзакций

    public function openFile ($dirName, ?callable $transactionHandler): array
    {

        try {
            $file = fopen($dirName, 'r');
            if ($file === false) {
                throw new \PDOException(' Не найден файл');
            }
            fgetcsv($file);
            $transactions = [];
            while (($transaction = fgetcsv($file)) !== false) {

                if ($transactionHandler !== null) {
                    $transaction = call_user_func($transactionHandler, $transaction);
                }

                $transactions[] = $transaction;
            }
            fclose($file);
            return $transactions;
        } catch (\Exception $e) {
            http_response_code(500);

            echo View::make('500', ['message' => $e->getMessage()]);
            return [];
        }
    }

    public function extractTransaction ($transaction): array
    {
        [$date, $check_number, $description, $amount] = $transaction;

        return
            [
                'date' => TransactionsFormatter::dateDB($date),
                'check_number' => $check_number !== '' ? (int) $check_number : null,
                'description' => $description,
                'amount' => TransactionsFormatter::amountDB($amount)
            ];
    }


}