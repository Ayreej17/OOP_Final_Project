public function up(): void
{
    Schema::create('menu_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('category_id')->constrained()->cascadeOnDelete();
        $table->string('name');
        $table->text('description')->nullable();
        $table->decimal('price', 10, 2);
        $table->integer('stock')->default(0);
        $table->boolean('is_available')->default(true);
        $table->timestamps();
    });
}
