<?php

namespace App\Domain\Payments;

interface PaymentProcessorInterface
{
    public function process(float $totalAmount, float $amountPaid, ?string $referenceNumber = null): array;

    public function getMethodName(): string;
}
