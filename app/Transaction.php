<?php

declare(strict_types=1);

namespace App;

class Transaction
{
    // Здесь должно быть открытие файла, и поиск пути к файлу транзакций

    public function openFile ($dirName, ?callable $transactionHandler): array
    {
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