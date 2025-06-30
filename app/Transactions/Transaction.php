<?php

namespace App\Transactions;

class Transaction
{
    public function readCsv(string $path): array
    {
        try {
            $file = fopen($path, 'r');
            if (!$file) {
                throw new \Exception('Error fetching transactions: ' . $path);
            }
            fgetcsv($file);
            $transactions = [];
            while (($transaction = fgetcsv($file)) !== false) {
                if (count(array_filter($transaction)) === 0) continue;
                $transactions[] = $transaction;
            }
            if (count($transactions) === 0) {
                throw new \Exception('No transactions found in the file: ' . $path);
            }
            fclose($file);
            return $transactions;
        } catch (\Exception $e) {
            http_response_code(404);
            throw new \Exception('Error reading CSV file: ' . $e->getMessage());
        }
    }

    public function formatTransactionDB(array $transactions): array
    {
        $formattedTransactions = [];
        foreach($transactions as $transaction) {
            if (count($transaction) <4) {
                throw new \Exception('Invalid transaction format: ' . implode(',', $transaction));
            }

            $formattedTransactions[] = [
                'date' => TransactionFormatter::formatDateDB($transaction[0]),
               'check_number' => (int) $transaction[1] ?: null,
                'description' => trim($transaction[2]),
                'amount' => TransactionFormatter::formatAmountDB($transaction[3])
            ];
        }

        return $formattedTransactions;
    }

    public function formatTransactionView(array $transactions): array
    {
        $formattedTransactions = [];
        foreach($transactions as $transaction) {
            if (count($transaction) < 4) {
                throw new \Exception('Invalid transaction format: ' . implode(',', $transaction));
            }

            $formattedTransaction = [
                'date' => TransactionFormatter::formatDateView($transaction['date']),
                'check_number' => (int) $transaction['check_number'] ?: ' ',
                'description' => trim($transaction['description']),
                'amount' => TransactionFormatter::formatAmountView($transaction['amount'])
            ];

            $formattedTransactions[] = $formattedTransaction;
        }

        return $formattedTransactions;
    }

    public function formatTotal(array $total): array
    {
        return [
            'income' => TransactionFormatter::formatAmountView($total['income']),
            'expense' => TransactionFormatter::formatAmountView($total['expense']),
            'net' => TransactionFormatter::formatAmountView($total['net'])
        ];
    }

}