public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('role')->default('staff')->after('email'); // 'admin' or 'staff'
        $table->boolean('is_active')->default(true)->after('role');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['role', 'is_active']);
    });
}
