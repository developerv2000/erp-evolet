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
        Schema::create('orders', function (Blueprint $table) {
            $table->unsignedMediumInteger('id')->autoIncrement();

            // Step 1:
            $table->unsignedMediumInteger('quantity');

            $table->unsignedInteger('process_id')
                ->index()
                ->foreign()
                ->references('id')
                ->on('processes');

            $table->date('receive_date');
            $table->timestamp('sent_to_bdm_date')->nullable(); // action

            // Step 2:
            $table->string('name')->nullable();
            $table->date('purchase_date')->nullable(); // auto filled when field 'name' filled
            $table->decimal('price', 8, 2)->nullable();

            $table->unsignedSmallInteger('currency_id')
                ->index()
                ->foreign()
                ->references('id')
                ->on('currencies')
                ->nullable();

            $table->timestamp('sent_to_confirmation_date')->nullable(); // action

            // Step 3:
            $table->timestamp('confirmation_date')->nullable(); // action

            // Step 4:
            $table->timestamp('sent_to_manufacturer_date')->nullable(); // action

            // Step 5:
            // CMD BDM part
            // $table->string('expected_dispatch_date')->nullable();
            // $table->date('receiving_prepayment_invoice_date')->nullable();
            // $table->timestamp('prepayment_requested_date')->nullable(); // action

            // DD Designer part
            $table->boolean('new_layout')->nullable();
            $table->date('date_of_sending_new_layout_to_manufacturer')->nullable();
            $table->date('date_of_receiving_print_proof_from_manufacturer')->nullable(); // required only when 'new_layout' is true
            $table->string('box_article')->nullable();
            $table->timestamp('layout_approved_date')->nullable(); // manually filled (similar to action)

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
