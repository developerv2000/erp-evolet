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

            $table->unsignedTinyInteger('type_id') // 'Production', 'Delivery to warehouse' or 'Export'
                ->foreign()
                ->references('id')
                ->on('invoice_types');

            // CMD or ELD part
            $table->timestamp('receive_date')->nullable();
            $table->string('pdf');
            $table->string('payment_company')->nullable(); // only for invoices of 'Delivery to warehouse' and 'Export' type

            $table->unsignedMediumInteger('order_id')
                ->index()
                ->foreign()
                ->references('id')
                ->on('orders');

            $table->unsignedTinyInteger('payment_type_id') // 'Prepayment', 'Final payment' or 'Full payment'.
                ->index()                                  // Always 'Full payment' for invoices of
                ->foreign()                                // 'Delivery to warehouse' and 'Export' type
                ->references('id')
                ->on('invoice_payment_types');

            $table->timestamp('sent_for_payment_date')->nullable(); // action (by BDM or ELD)

            // PRD part
            $table->timestamp('accepted_by_financier_date')->nullable();
            $table->timestamp('payment_request_date_by_financier')->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->string('number')->nullable();
            $table->string('payment_confirmation_document')->nullable(); // SWIFT
            $table->timestamp('payment_completed_date')->nullable(); // action

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('invoice_order_product', function (Blueprint $table) {
            $table->unsignedSmallInteger('invoice_id')
                ->foreign()
                ->references('id')
                ->on('invoices');

            $table->unsignedSmallInteger('order_product_id')
                ->foreign()
                ->references('id')
                ->on('order_products');

            $table->primary(['invoice_id', 'order_product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('invoice_order_product');
    }
};
