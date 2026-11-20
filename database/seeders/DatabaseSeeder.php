<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Default Admin & Staff Accounts
        User::create([
            'name' => 'Admin Manager',
            'email' => 'admin@restaurant.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Cashier Staff',
            'email' => 'staff@restaurant.test',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'is_active' => true,
        ]);

        // 2. Sample Categories & Menu Items
        $mains = Category::create(['name' => 'Main Course', 'description' => 'Rice meals and signature entrees']);
        $drinks = Category::create(['name' => 'Beverages', 'description' => 'Cold refreshers, coffee, and shakes']);
        $desserts = Category::create(['name' => 'Desserts', 'description' => 'Sweet treats and pastries']);

        MenuItem::create(['category_id' => $mains->id, 'name' => 'Chicken Adobo Rice Bowl', 'price' => 145.00, 'stock' => 30, 'is_available' => true]);
        MenuItem::create(['category_id' => $mains->id, 'name' => 'Crispy Pork Sisig', 'price' => 185.00, 'stock' => 25, 'is_available' => true]);
        MenuItem::create(['category_id' => $mains->id, 'name' => 'Grilled Tuna Belly', 'price' => 220.00, 'stock' => 15, 'is_available' => true]);
        MenuItem::create(['category_id' => $drinks->id, 'name' => 'Calamansi Iced Tea', 'price' => 55.00, 'stock' => 50, 'is_available' => true]);
        MenuItem::create(['category_id' => $drinks->id, 'name' => 'Iced Caramel Macchiato', 'price' => 110.00, 'stock' => 40, 'is_available' => true]);
        MenuItem::create(['category_id' => $desserts->id, 'name' => 'Leche Flan', 'price' => 75.00, 'stock' => 20, 'is_available' => true]);
    }
}
