<?php

namespace App\Domain\Payments;

use Exception;

class GCashPayment implements PaymentProcessorInterface
{
    public function process(float $totalAmount, float $amountPaid, ?string $referenceNumber = null): array
    {
        if (empty(trim((string) $referenceNumber))) {
            throw new Exception('GCash reference number is required.');
        }

        if ($amountPaid < $totalAmount) {
            throw new Exception('GCash payment amount is less than total due.');
        }

        return [
            'status' => 'paid',
            'amount_paid' => $totalAmount,
            'change_amount' => 0.00,
            'reference_number' => trim($referenceNumber),
        ];
    }

    public function getMethodName(): string
    {
        return 'gcash';
    }
}
