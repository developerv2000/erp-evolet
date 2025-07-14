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

            $table->unsignedSmallInteger('serialization_type_id')
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

            // Step 7:
            // MSD part
            $table->timestamp('serialization_codes_request_date')->nullable();
            $table->timestamp('serialization_codes_sent_date')->nullable();
            $table->timestamp('serialization_report_recieved_date')->nullable();
            $table->timestamp('report_sent_to_hub_date')->nullable();

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
