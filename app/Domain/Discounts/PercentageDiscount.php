<?php

namespace App\Domain\Discounts;

class PercentageDiscount extends Discount
{
    public function __construct(
        private float $percentage,
        private string $label = 'Percentage Discount'
    ) {}

    public function calculate(float $subtotal): float
    {
        return round($subtotal * ($this->percentage / 100), 2);
    }

    public function getLabel(): string
    {
        return "{$this->label} ({$this->percentage}%)";
    }
}
