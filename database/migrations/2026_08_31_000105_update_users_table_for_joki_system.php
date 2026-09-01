<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->string('username')->nullable()->after('name')->unique();
            $table->string('phone')->nullable()->after('email');
            $table->string('avatar_path')->nullable()->after('password');
            $table->string('wa_number')->nullable()->after('avatar_path');
            $table->text('bio')->nullable()->after('wa_number');
            $table->decimal('balance', 12, 2)->default(0)->after('bio');
            $table->boolean('is_active')->default(true)->after('balance');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('role_id');
            $table->dropColumn(['username', 'phone', 'avatar_path', 'wa_number', 'bio', 'balance', 'is_active', 'last_login_at']);
        });
    }
};
