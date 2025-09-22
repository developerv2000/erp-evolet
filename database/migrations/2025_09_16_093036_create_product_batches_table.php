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

            // Step 2:
            // PLPD part
            $table->timestamp('serialization_request_date')->nullable(); // action
            $table->unsignedMediumInteger('number_of_full_boxes')->nullable();
            $table->unsignedMediumInteger('number_of_packages_in_full_box')->nullable();

            $table->unsignedMediumInteger('number_of_incomplete_boxes')->nullable(); // auto (0 or 1)
            $table->unsignedMediumInteger('number_of_packages_in_incomplete_box')->nullable(); // auto

            $table->string('additional_comment')->nullable();

            // Step 3:
            // MSD part
            $table->date('serialization_start_date')->nullable();
            $table->unsignedMediumInteger('factual_quantity')->nullable();
            $table->unsignedMediumInteger('defects_quantity')->nullable();
            $table->date('serialization_end_date')->nullable();

            $table->timestamp('serialization_codes_request_date')->nullable();
            $table->timestamp('serialization_codes_sent_date')->nullable();
            $table->timestamp('serialization_report_recieved_date')->nullable();
            $table->timestamp('report_sent_to_hub_date')->nullable();

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
