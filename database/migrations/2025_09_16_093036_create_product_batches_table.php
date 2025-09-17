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
        Schema::create('product_batches', function (Blueprint $table) {
            $table->unsignedMediumInteger('id')->autoIncrement();

            // Step 1:
            // ELD part
            $table->unsignedMediumInteger('order_product_id')
                ->index()
                ->foreign()
                ->references('id')
                ->on('order_products');

            $table->string('series');
            $table->date('manufacturing_date')->nullable();
            $table->date('expiration_date')->nullable();
            $table->unsignedMediumInteger('quantity');

            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_batches');
    }
};
