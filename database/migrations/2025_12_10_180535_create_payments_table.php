<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_id')->unique(); // external or internal id
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('status'); // pending, successful, failed
            $table->string('method'); // credit_card, paypal, etc
            $table->decimal('amount', 12, 2);
            $table->json('response')->nullable(); // gateway response
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
