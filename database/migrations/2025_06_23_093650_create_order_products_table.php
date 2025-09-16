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
        Schema::create('order_products', function (Blueprint $table) {
            $table->unsignedMediumInteger('id')->autoIncrement();

            // Step 1:
            // PLPD part
            $table->unsignedMediumInteger('order_id')
                ->index()
                ->foreign()
                ->references('id')
                ->on('orders');

            $table->unsignedInteger('process_id')
                ->index()
                ->foreign()
                ->references('id')
                ->on('processes');

            $table->unsignedTinyInteger('serialization_type_id')
                ->index()
                ->foreign()
                ->references('id')
                ->on('serialization_types');

            $table->unsignedMediumInteger('quantity');

            // Step 2:
            // CMD part
            $table->decimal('price', 8, 2)->nullable();

            // Step 5:
            // DD Designer part
            $table->boolean('new_layout')->default(false);
            $table->date('date_of_sending_new_layout_to_manufacturer')->nullable();
            $table->date('date_of_receiving_print_proof_from_manufacturer')->nullable(); // required only when 'new_layout' is true
            $table->string('box_article')->nullable();
            $table->date('layout_approved_date')->nullable(); // manually filled (similar to action)

            // Step 6:
            // CMD part
            $table->text('production_status')->nullable();
            $table->timestamp('production_end_date')->nullable(); // action

            // Step 7:
            // MSD part
            $table->timestamp('serialization_codes_request_date')->nullable();
            $table->timestamp('serialization_codes_sent_date')->nullable();
            $table->timestamp('serialization_report_recieved_date')->nullable();
            $table->timestamp('report_sent_to_hub_date')->nullable();

            // Step 8:
            // CMD part
            $table->string('packing_list_file')->nullable();
            $table->string('coa_file')->nullable();
            $table->string('coo_file')->nullable();
            $table->string('declaration_for_europe_file')->nullable();
            $table->timestamp('readiness_for_shipment_from_manufacturer_date')->nullable(); // action

            // Step 9:
            // ELD part
            $table->timestamp('shipment_from_manufacturer_start_date')->nullable();
            $table->string('shipment_from_manufacturer_id')->nullable();
            $table->unsignedTinyInteger('shipment_from_manufacturer_volume')->nullable();
            $table->string('shipment_from_manufacturer_packs')->nullable();

            $table->unsignedTinyInteger('shipment_from_manufacturer_type_id') // 'Auto', 'Air' or 'Sea'
                ->index()
                ->foreign()
                ->references('id')
                ->on('shipment_types')
                ->nullable();

            $table->unsignedTinyInteger('shipment_from_manufacturer_destination_id') // 'Riga' or 'Destination country'
                ->index()
                ->foreign()
                ->references('id')
                ->on('shipment_destinations')
                ->nullable();

            // Step 10:
            // ELD part
            $table->timestamp('delivery_to_warehouse_request_date')->nullable(); // action
            $table->date('delivery_to_warehouse_rate_approved_date')->nullable(); // manually filled (similar to action)
            $table->string('delivery_to_warehouse_forwarder')->nullable();
            $table->unsignedMediumInteger('delivery_to_warehouse_price')->nullable();

            $table->unsignedTinyInteger('delivery_to_warehouse_currency_id')
                ->index()
                ->foreign()
                ->references('id')
                ->on('currencies')
                ->nullable();

            $table->date('delivery_to_warehouse_loading_confirmed_date')->nullable();
            $table->timestamp('shipment_from_manufacturer_end_date')->nullable(); // action

            // Step 11:
            // ELD part
            $table->timestamp('warehouse_arrival_date')->nullable(); // action
            $table->string('warehouse_invoice_number')->nullable();
            $table->unsignedTinyInteger('payer_company_id')->nullable();
            $table->string('customs_code')->nullable();
            $table->unsignedMediumInteger('factual_quantity');
            $table->unsignedMediumInteger('full_packs_in_boxes');
            $table->unsignedMediumInteger('part_packs_in_box');
            $table->unsignedMediumInteger('defects_quantity');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_products');
    }
};
