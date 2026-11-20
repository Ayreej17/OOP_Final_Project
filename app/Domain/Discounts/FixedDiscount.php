<?php

namespace App\Domain\Discounts;

class FixedDiscount extends Discount
{
    public function __construct(
        private float $amount,
        private string $label = 'Fixed Discount'
    ) {}

    public function calculate(float $subtotal): float
    {
        return min($this->amount, $subtotal);
    }

    public function getLabel(): string
    {
        return "{$this->label} (₱{$this->amount})";
    }
}
