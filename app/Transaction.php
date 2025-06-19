<?php

namespace App;

use App\Exceptions\TransactionGetException;

class Transaction
{
    /** Здесь будет логика извлечения данных из .csv
     * Здесь получение имени файла, и передача в массиве
     * Также должен быть метод, который будет изменять данные поступаемые в транзакцию
    **/

    public function getTransactions(string $fileName, ?callable $transactionHandler): array
    {
        if (!file_exists($fileName)) {
            http_response_code(404);

            throw new TransactionGetException();
        }

        $file = fopen($fileName, 'r');

        if ($file === false) {

            http_response_code(500);

            throw new TransactionGetException('Failed to open file: ' . $fileName);
        }

        fgetcsv($file);

        $transactions = [];

        while (($transaction = fgetcsv($file)) !== false) {
            if ($transactionHandler !== null) {
                $transaction = $transactionHandler($transaction);
            }

            $transactions[] = $transaction;
        }

        fclose($file);

        return $transactions;
    }

    public function getTransactionsFiles (string $dirPath): array {

        $files = [];

        foreach (scandir($dirPath) as $file) {
            if (is_file($dirPath . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'csv') {
                $files[] = $dirPath . DIRECTORY_SEPARATOR . $file;
            }
        }

        return $files;
    }

    public function extractTransaction (array $transactions): array {
        $key = ['date', 'check_number', 'descriptions', 'amount'];
        $transactions = array_combine($key, $transactions);
        [$date, $checkNumber, $descriptions, $amount] = array_values($transactions);

        $date = date('Y-m-d', strtotime($date));
        $checkNumber = (int) $checkNumber ?? null;
        $descriptions = trim($descriptions);
        $amount = (float) str_replace(['$', ','], '', $amount);

        return [
            'date' => Helper::formatDate($date),
            'check_number' => $checkNumber,
            'descriptions' => $descriptions,
            'amount' => Helper::formatAmount($amount)
        ];
    }
}