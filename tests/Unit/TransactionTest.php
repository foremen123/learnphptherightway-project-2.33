<?php

use App\TransactionHelper\Transaction;
use PHPUnit\Framework\TestCase;

class TransactionTest extends TestCase
{
    public function test_for_changing_the_extracted_transaction(): void
    {

        $transaction = new Transaction();

        $transactions = [
            '23/06/2025',
            '123',
            'Test Transaction',
            '$100.50',
        ];

        $result = $transaction->extractTransaction($transactions);

        $expected =
           [
               'date' => '2025-06-23',
               'check_number' =>123,
               'description' =>'Test Transaction',
               'amount' =>100.50,
           ];
        $this->assertSame($expected, $result);
    }
}