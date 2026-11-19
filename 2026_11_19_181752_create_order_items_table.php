public function up(): void
{
    Schema::create('order_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_id')->constrained()->cascadeOnDelete();
        $table->foreignId('menu_item_id')->constrained();
        $table->integer('quantity');
        $table->decimal('unit_price', 10, 2);
        $table->decimal('subtotal', 10, 2);
        $table->timestamps();
    });
}
