<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('worker_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('game_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->foreignId('rank_from_id')->nullable()->constrained('ranks')->nullOnDelete();
            $table->foreignId('rank_to_id')->nullable()->constrained('ranks')->nullOnDelete();
            $table->string('order_number')->unique();
            $table->string('status')->default('pending');
            $table->string('priority')->default('normal');
            $table->decimal('price', 12, 2)->default(0);
            $table->string('payment_method')->default('midtrans');
            $table->string('payment_status')->default('pending');
            $table->string('wa_number')->nullable();
            $table->longText('account_credentials')->nullable();
            $table->string('request_hero')->nullable();
            $table->text('notes')->nullable();
            $table->string('tracking_code')->nullable()->unique();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('delivery_deadline_at')->nullable();
            $table->unsignedTinyInteger('worker_progress')->default(0);
            $table->unsignedTinyInteger('customer_rating')->nullable();
            $table->text('customer_review')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
