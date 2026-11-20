<?php

namespace App\Domain\Payments;

use Exception;

class CashPayment implements PaymentProcessorInterface
{
    public function process(float $totalAmount, float $amountPaid, ?string $referenceNumber = null): array
    {
        if ($amountPaid < $totalAmount) {
            throw new Exception('Insufficient cash tendered.');
        }

        return [
            'status' => 'paid',
            'amount_paid' => $amountPaid,
            'change_amount' => round($amountPaid - $totalAmount, 2),
            'reference_number' => null,
        ];
    }

    public function getMethodName(): string
    {
        return 'cash';
    }
}
