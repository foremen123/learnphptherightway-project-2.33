<?php

declare(strict_types=1);

namespace App;

class TransactionsCalculate
{
    // Здесь будет расчет итого дохода, расхода и итоговая

     public static function totalCalculate(array $transactions): array
     {
         $total = [
             'income' => 0,
             'expense' => 0,
             'net'
         ];

         foreach ($transactions as $transaction) {
             $amount = $transaction['amount'];

             if ($amount > 0) {
                 $total['income'] += $amount;
             } else if ($amount < 0) {
                 $total['expense'] += abs($amount);
             }
         }

         $total['net'] = $total['income'] - $total['expense'];

         return $total;
     }

     public static function totalFormatted (array $amount): array
         {
             return [
                 'income' => TransactionsFormatter::amountDollars($amount['income']),
                 'expense' => TransactionsFormatter::amountDollars($amount['expense']),
                 'net' => TransactionsFormatter::amountDollars($amount['net']),
             ];
         }
}