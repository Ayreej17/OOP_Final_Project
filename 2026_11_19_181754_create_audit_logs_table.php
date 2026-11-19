public function up(): void
{
    Schema::create('audit_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
        $table->string('action'); // e.g., 'ORDER_CANCELLED', 'MENU_PRICE_UPDATED', 'USER_DEACTIVATED'
        $table->string('model_type'); // e.g., 'Order', 'MenuItem', 'User'
        $table->unsignedBigInteger('model_id')->nullable();
        $table->text('description');
        $table->timestamps();
    });
}
