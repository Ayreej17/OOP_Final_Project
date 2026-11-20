<?php

namespace App\Domain\Inventory;

use App\Models\MenuItem;
use Exception;

class StockManager
{
    public function deduct(MenuItem $item, int $quantity): void
    {
        if ($quantity <= 0) {
            throw new Exception('Quantity must be greater than zero.');
        }

        if ($item->stock < $quantity) {
            throw new Exception("Insufficient stock for {$item->name}. Available: {$item->stock}");
        }

        $item->decrement('stock', $quantity);
    }

    public function restore(MenuItem $item, int $quantity): void
    {
        if ($quantity > 0) {
            $item->increment('stock', $quantity);
        }
    }
}
