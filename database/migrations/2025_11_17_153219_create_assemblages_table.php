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
        Schema::create('assemblages', function (Blueprint $table) {
            $table->unsignedMediumInteger('id')->autoIncrement();

            // PLPD part
            $table->string('number');
            $table->date('date');

            $table->unsignedTinyInteger('shipment_type_id') // 'Auto', 'Air' or 'Sea'
                ->index()
                ->foreign()
                ->references('id')
                ->on('shipment_types')
                ->nullable();

            $table->unsignedSmallInteger('country_id') // market
                ->index()
                ->foreign()
                ->references('id')
                ->on('countries');

            $table->timestamps();
        });

        Schema::create('assemblage_order_product', function (Blueprint $table) {
            $table->unsignedMediumInteger('assemblage_id')
                ->foreign()
                ->references('id')
                ->on('assemblages');

            $table->unsignedMediumInteger('order_product_id')
                ->foreign()
                ->references('id')
                ->on('products');

            // Pivot data
            $table->unsignedInteger('quantity_for_assembly');

            $table->primary(['assemblage_id', 'order_product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assemblages');
        Schema::dropIfExists('assemblage_order_product');
    }
};
