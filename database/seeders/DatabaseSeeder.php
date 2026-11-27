<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Default Admin & Staff
        $admin = User::firstOrCreate(
            ['email' => 'admin@restaurant.test'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        $staff = User::firstOrCreate(
            ['email' => 'staff@restaurant.test'],
            [
                'name' => 'Staff Member',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'is_active' => true,
            ]
        );

        // 2. Create Categories
        $catMains = Category::firstOrCreate(['name' => 'Main Course'], ['description' => 'Heavy meals & specialties']);
        $catDrinks = Category::firstOrCreate(['name' => 'Beverages'], ['description' => 'Cold & hot drinks']);
        $catDesserts = Category::firstOrCreate(['name' => 'Desserts'], ['description' => 'Sweet treats & pastries']);

        // 3. Create Menu Items
        $items = [
            ['name' => 'Crispy Pork Belly', 'category_id' => $catMains->id, 'price' => 280.00, 'stock' => 50],
            ['name' => 'Garlic Butter Shrimp', 'category_id' => $catMains->id, 'price' => 320.00, 'stock' => 40],
            ['name' => 'Creamy Carbonara', 'category_id' => $catMains->id, 'price' => 220.00, 'stock' => 60],
            ['name' => 'Iced Caramel Macchiato', 'category_id' => $catDrinks->id, 'price' => 135.00, 'stock' => 100],
            ['name' => 'Classic Milk Tea', 'category_id' => $catDrinks->id, 'price' => 110.00, 'stock' => 120],
            ['name' => 'Halo-Halo Special', 'category_id' => $catDesserts->id, 'price' => 150.00, 'stock' => 45],
            ['name' => 'Mango Float Slice', 'category_id' => $catDesserts->id, 'price' => 120.00, 'stock' => 30],
            ['name' => 'Fruit Salad', 'category_id' => $catDesserts->id, 'price' => 100.00, 'stock' => 20],
            ['name' => 'Mango Pie', 'category_id' => $catDesserts->id, 'price' => 50.00, 'stock' => 30],
            ['name' => 'Coca-Cola', 'category_id' => $catDrinks->id, 'price' => 75.00, 'stock' => 120],
            ['name' => 'Spaghetti', 'category_id' => $catMains->id, 'price' => 190.00, 'stock' => 40]            
        ];

        $createdItems = [];
        foreach ($items as $data) {
            $createdItems[] = MenuItem::firstOrCreate(['name' => $data['name']], $data);
        }

        // 4. Create Sample Orders for Reports & Status Board
        $ordersData = [
            ['status' => 'completed', 'type' => 'dine_in', 'table' => 'T-01', 'days_ago' => 0],
            ['status' => 'completed', 'type' => 'takeout', 'table' => null,   'days_ago' => 1],
            ['status' => 'completed', 'type' => 'dine_in', 'table' => 'T-04', 'days_ago' => 2],
            ['status' => 'completed', 'type' => 'dine_in', 'table' => 'T-02', 'days_ago' => 3],
            ['status' => 'preparing', 'type' => 'dine_in', 'table' => 'T-05', 'days_ago' => 0],
            ['status' => 'pending',   'type' => 'takeout', 'table' => null,   'days_ago' => 0],
            ['status' => 'pending',   'type' => 'dine_in', 'table' => 'T-03', 'days_ago' => 0],
        ];

        foreach ($ordersData as $idx => $ord) {
            $date = Carbon::now()->subDays($ord['days_ago'])->subHours(rand(1, 8));
            $order = Order::create([
                'order_number' => 'ORD-'.strtoupper(substr(uniqid(), 7)),
                'user_id' => $staff->id,
                'order_type' => $ord['type'],
                'table_number' => $ord['table'],
                'status' => $ord['status'],
                'subtotal' => 0,
                'discount' => 0,
                'grand_total' => 0,
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            // Add 2-3 items per order
            $subtotal = 0;
            $sampleItems = collect($createdItems)->random(rand(2, 3));
            foreach ($sampleItems as $menuItem) {
                $qty = rand(1, 3);
                $lineTotal = $menuItem->price * $qty;
                $subtotal += $lineTotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $menuItem->id,
                    'quantity' => $qty,
                    'unit_price' => $menuItem->price,
                    'subtotal' => $lineTotal,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }

            $order->update([
                'subtotal' => $subtotal,
                'grand_total' => $subtotal,
            ]);
        }

        // 5. Initial Audit Logs
        AuditLog::create([
            'user_id' => $admin->id,
            'action' => 'SYSTEM_INITIALIZATION',
            'description' => 'Database successfully migrated and seeded with master restaurant data.',
            'created_at' => Carbon::now()->subDays(3),
        ]);
        AuditLog::create([
            'user_id' => $staff->id,
            'action' => 'USER_LOGIN',
            'description' => 'Staff user signed in to the restaurant POS workstation.',
            'created_at' => Carbon::now()->subHours(2),
        ]);
    }
}
