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
            $table->unsignedInteger('manufacturer_id')
                ->index()
                ->foreign()
                ->references('id')
                ->on('manufacturers');

            $table->unsignedSmallInteger('country_id')
                ->index()
                ->foreign()
                ->references('id')
                ->on('countries');

            $table->date('receive_date');
            $table->timestamp('sent_to_bdm_date')->nullable(); // action

            // Step 2:
            $table->string('name')->nullable();
            $table->date('purchase_date')->nullable(); // auto filled when field 'name' filled

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
