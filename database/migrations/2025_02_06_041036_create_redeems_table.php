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
        Schema::create('redeems', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('booking_trx_id');
            $table->decimal('total_redeem_price', 10, 0);
            $table->enum('method', ['cash', 'transfer'])->default('cash');
            $table->string('bank_number')->nullable();  // Data bank user jika transfer
            $table->decimal('tax', 10, 0);
            $table->decimal( 'grand_redeem_total', 10, 0);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('redeems');
    }
};
