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
        Schema::create('invoices', function (Blueprint $table) {
            $table->unsignedMediumInteger('id')->autoIncrement();
            $table->timestamp('receive_date');
            $table->string('filename');

            $table->unsignedMediumInteger('order_id')
                ->index()
                ->foreign()
                ->references('id')
                ->on('orders');

            $table->unsignedTinyInteger('payment_type_id') // 'Prepayment', 'Final payment' or 'Full payment'
                ->index()
                ->foreign()
                ->references('id')
                ->on('invoice_payment_types');

            $table->timestamp('sent_for_payment_date')->nullable(); // action

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
