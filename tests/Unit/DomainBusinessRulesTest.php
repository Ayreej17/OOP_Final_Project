<?php

use App\Domain\Discounts\FixedDiscount;
use App\Domain\Discounts\PercentageDiscount;
use App\Domain\Inventory\StockManager;
use App\Domain\Payments\CashPayment;
use App\Domain\Payments\GCashPayment;
use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('percentage and fixed discounts calculate accurate reductions', function () {
    $percent = new PercentageDiscount(10); // 10%
    $fixed = new FixedDiscount(50);        // ₱50 off

    expect($percent->calculate(500.00))->toBe(50.0)
        ->and($fixed->calculate(500.00))->toBe(50.0);
});

test('stock manager deducts and restores stock without allowing negative values', function () {
    $category = Category::create(['name' => 'Main Dish']);
    $item = MenuItem::create([
        'category_id'  => $category->id,
        'name'         => 'Adobo',
        'price'        => 120.00,
        'stock'        => 5,
        'is_available' => true,
    ]);

    $manager = new StockManager();

    $manager->deduct($item, 3);
    expect($item->fresh()->stock)->toBe(2);

    $manager->restore($item, 2);
    expect($item->fresh()->stock)->toBe(4);

    expect(fn () => $manager->deduct($item->fresh(), 10))->toThrow(Exception::class);
});

test('payment processors compute cash change and validate gcash references', function () {
    $cash = new CashPayment();
    $cashResult = $cash->process(350.00, 500.00);
    expect($cashResult['status'])->toBe('paid')
        ->and($cashResult['change_amount'])->toBe(150.0)
        ->and($cashResult['reference_number'])->toBeNull();

    expect(fn () => $cash->process(350.00, 200.00))->toThrow(Exception::class);

    $gcash = new GCashPayment();
    $gcashResult = $gcash->process(350.00, 350.00, '  GCASH-998877  ');
    expect($gcashResult['status'])->toBe('paid')
        ->and($gcashResult['change_amount'])->toBe(0.0)
        ->and($gcashResult['reference_number'])->toBe('GCASH-998877');
});
