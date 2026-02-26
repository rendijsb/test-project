<?php

use App\Enums\Orders\OrderStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', OrderStatusEnum::values())->default(OrderStatusEnum::PENDING->value);
            $table->decimal('total_amount', 10, 2);
            $table->text('description')->nullable();
            $table->date('order_date');

            $table->timestamps();

            $table->index('customer_id');
            $table->index('user_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
