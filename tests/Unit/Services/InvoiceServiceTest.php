<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\EmailService;
use App\Services\InvoiceService;
use App\Services\PaymentGatewayService;
use App\Services\SalesTaxService;
use PHPUnit\Framework\TestCase;

class InvoiceServiceTest extends  TestCase
{
    public function test_processInvoice(): void
    {
        //Given

        $salesTaxServiceMock = $this->createMock(salesTaxService::class);
        $PaymentGatewayServiceMock = $this->createMock(PaymentGatewayService::class);
        $emailServiceMock = $this->createMock(EmailService::class);

        $invoiceService = new InvoiceService(
            $salesTaxServiceMock,
            $PaymentGatewayServiceMock,
            $emailServiceMock
        );

        $PaymentGatewayServiceMock ->method('charge')->willReturn(true);

        $customer = [
            'name' => 'John Doe',
        ];

        $amount = 10000;

        //When

        $result = $invoiceService->process($customer, $amount);
        //Then

        $this->assertTrue($result);


    }

    public function test_sends_receipt_email_when_invoice_is_processed(): void
    {
        // Given
        $salesTaxServiceMock = $this->createMock(salesTaxService::class);
        $PaymentGatewayServiceMock = $this->createMock(PaymentGatewayService::class);
        $emailServiceMock = $this->createMock(EmailService::class);

        $PaymentGatewayServiceMock ->method('charge')->willReturn(true);

        $emailServiceMock
            ->expects($this->once())
            ->method('send')
            ->with(['name' => 'John Doe'], 'receipt');

        $invoiceService = new InvoiceService(
            $salesTaxServiceMock,
            $PaymentGatewayServiceMock,
            $emailServiceMock
        );

        $customer = [
            'name' => 'John Doe',
        ];

        $amount = 10000;

        //When

        $result = $invoiceService->process($customer, $amount);
        //Then

        $this->assertTrue($result);
    }
}