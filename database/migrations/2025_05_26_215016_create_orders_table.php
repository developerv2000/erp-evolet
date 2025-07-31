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
            // PLPD part
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
            // CMD part
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
            // PLPD part
            $table->timestamp('confirmation_date')->nullable(); // action

            // Step 4:
            // CMD part
            $table->timestamp('sent_to_manufacturer_date')->nullable(); // action

            // Step 5:
            // PLPD part
            $table->string('expected_dispatch_date')->nullable();

            // Step 6:
            // CMD part

            // Production starts for all products of the order at the same time,
            // but can be finished at the different times.
            $table->timestamp('production_start_date')->nullable(); // action

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
