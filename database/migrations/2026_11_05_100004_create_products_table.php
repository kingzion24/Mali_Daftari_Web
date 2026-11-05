<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('name');
            $table->string('size_label')->comment('e.g. 500ml, 1L, 20L, sachet');
            $table->string('type')->comment('Allowed values: bottle, dispenser, sachet');
            $table->decimal('unit_price', 12, 2);
            $table->boolean('is_returnable')->default(false);
            $table->decimal('deposit_amount', 12, 2)->nullable();
            $table->integer('reorder_level')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};