public function up(): void
{
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->string('order_number')->unique(); // e.g., ORD-20261120-001
        $table->foreignId('user_id')->constrained(); // Cashier/Staff who created it
        $table->string('order_type')->default('dine_in'); // 'dine_in' or 'takeout'
        $table->string('table_number')->nullable(); // Used for dine-in orders
        $table->string('status')->default('pending'); // 'pending', 'preparing', 'completed', 'cancelled'
        $table->string('discount_type')->nullable(); // 'none', 'percentage', 'fixed'
        $table->decimal('subtotal', 10, 2)->default(0);
        $table->decimal('discount_amount', 10, 2)->default(0);
        $table->decimal('total_amount', 10, 2)->default(0);
        $table->timestamps();
    });
}
