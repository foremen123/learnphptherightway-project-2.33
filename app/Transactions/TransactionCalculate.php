<?php

declare(strict_types=1);

namespace App\Transactions;

class TransactionCalculate
{
    public static function calculate(array $transactions): array
    {
        $total = [
            'income' => 0,
            'expense' => 0,
            'net' => 0,
        ];

        foreach ($transactions as $transaction) {

            $amount = (float) $transaction['amount'];

            if ($amount > 0) {
                $total['income'] += $amount;
            } elseif ($amount < 0) {
                $total['expense'] += abs($amount);
            }
        }

        $total['net'] = $total['income'] - $total['expense'];

        return $total;

    }
}