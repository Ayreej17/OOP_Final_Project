<?php

namespace App\Domain\Discounts;

abstract class Discount
{
    abstract public function calculate(float $subtotal): float;

    abstract public function getLabel(): string;
}
