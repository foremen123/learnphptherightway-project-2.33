<?php

namespace Tests\Unit\Models;

use App\Models\TransactionsModel;
use PDO;
use PDOException;
use PDOStatement;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TransactionsModelTest extends TestCase
{
    protected TransactionsModel $object;

    protected MockObject $dbMock;

    protected PDOStatement $stmtMock;

    public function setUp (): void
    {
        parent::setUp();

        $this->dbMock = $this->createMock(PDO::class);
        $this->stmtMock = $this->createMock(PDOStatement::class);

        $this->object = new class ($this->dbMock) extends TransactionsModel {
            public function __construct($db)
            {
                $this->db = $db;
            }
        };
    }

    public function test_add_transaction_executes_db_operation_successfully(): void
    {
        $transaction = [
            'date' => '23/06/2025',
            'check_number' => '12345',
            'description' => 'Test transaction',
            'amount' => 100.00,
        ];

        $this->dbMock
            ->expects($this->once())
            ->method('beginTransaction')
            ->willReturn(true);

        $this->dbMock
            ->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO transactions (date, check_number, description, amount) VALUES (?, ?, ?, ?)')
            ->willReturn($this->stmtMock);

        $this->stmtMock
            ->expects($this->once())
            ->method('execute')
            ->with(
                [
                    $transaction['date'],
                    $transaction['check_number'],
                    $transaction['description'],
                    $transaction['amount'],
                ])
            ->willReturn(true);

        $this->dbMock
            ->expects($this->once())
            ->method('commit')
            ->willReturn(true);

        $this->object->addTransactions([$transaction]);
    }

    public function test_add_transaction_executes_db_operation_failure(): void
    {
        $transaction = [
            'date' => '23/06/2025',
            'check_number' => '12434',
            'description' => 'Test transaction',
            'amount' => 100,00,
        ];

        $this->dbMock
            ->expects($this->once())
            ->method('beginTransaction')
            ->willReturn(true);

        $this->dbMock
            ->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO transactions (date, check_number, description, amount) VALUES (?, ?, ?, ?)')
            ->willReturn($this->stmtMock);

        $this->stmtMock
            ->expects($this->once())
            ->method('execute')
            ->with(
                [
                    $transaction['date'],
                    $transaction['check_number'],
                    $transaction['description'],
                    $transaction['amount'],
                ])
            ->willThrowException(new PDOException('Ошибка при вставке данных'));

        $this->expectException(PDOException::class);
        $this->object->addTransactions([$transaction]);
    }

    public function test_get_all_transactions_returns_formatted_data(): void
    {
        $transaction = [
            'date' => '2025-06-24',
            'check_number' => '12345',
            'description' => 'Test transaction',
            'amount' => 100.00,
        ];

        $this->dbMock
            ->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM transactions')
            ->willReturn($this->stmtMock);

        $this->stmtMock
            ->expects($this->once())
            ->method('execute')
            ->willReturn($this->stmtMock);

        $this->stmtMock
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturn([$transaction]);

        $expected = [
            [
                'date' => 'Jun-24-2025',
                'check_number' => 12345,
                'description' => 'Test transaction',
                'amount' => '$100.00',
            ]
        ];

        $result = $this->object->getAll();

        $this->assertSame($expected, $result);
    }
}