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

            // Step 1:
            // PLPD part
            $table->string('number');
            $table->date('application_date');

            $table->unsignedTinyInteger('shipment_type_id') // 'Auto', 'Air' or 'Sea'
                ->index()
                ->foreign()
                ->references('id')
                ->on('shipment_types');

            $table->unsignedSmallInteger('country_id') // market
                ->index()
                ->foreign() 
                ->references('id')
                ->on('countries');

            $table->timestamps('request_sent_date')->nullable(); // action

            // Step 2:
            // ELD part
            $table->timestamps('request_accepted_date')->nullable(); // action

            $table->date('inital_assembly_acceptance_date')->nullable();
            $table->string('inital_assembly_file')->nullable();
            $table->date('final_assembly_acceptance_date')->nullable();
            $table->string('final_assembly_file')->nullable();

            $table->date('documents_provision_date_to_warehouse')->nullable();

            $table->date('coo_file_date')->nullable();
            $table->string('coo_file')->nullable();
            $table->date('euro_1_file_date')->nullable();
            $table->string('euro_1_file')->nullable();
            $table->string('gmp_or_iso_file')->nullable();

            $table->unsignedMediumInteger('volume')->nullable();
            $table->string('packs')->nullable();

            // Step 3:
            // ELD part
            $table->timestamp('delivery_to_destination_country_request_date')->nullable(); // action
            $table->date('delivery_to_destination_country_rate_approved_date')->nullable(); // manually filled (similar to action)
            $table->string('delivery_to_destination_country_forwarder')->nullable();
            $table->unsignedMediumInteger('delivery_to_destination_country_price')->nullable();

            $table->unsignedTinyInteger('delivery_to_destination_country_currency_id')
                ->index()
                ->foreign()
                ->references('id')
                ->on('currencies')
                ->nullable();

            $table->date('delivery_to_destination_country_loading_confirmed_date')->nullable();
            $table->timestamp('shipment_from_warehouse_end_date')->nullable(); // action

            // Timestamps
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
