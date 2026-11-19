public function up(): void
{
    Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_id')->constrained()->cascadeOnDelete();
        $table->string('payment_method'); // 'cash', 'gcash', 'card'
        $table->decimal('amount_paid', 10, 2);
        $table->decimal('change_amount', 10, 2)->default(0);
        $table->string('reference_number')->nullable(); // Useful for GCash/Card
        $table->string('status')->default('paid');
        $table->timestamps();
    });
}
