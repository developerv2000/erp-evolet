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
        Schema::create('processes', function (Blueprint $table) {
            // Globals
            $table->unsignedInteger('id')->autoIncrement(); // auto
            $table->boolean('contracted_in_asp')->default(false); // SPG
            $table->boolean('registered_in_asp')->default(false); // SPG

            // auto
            $table->unsignedInteger('product_id')
                ->index()
                ->foreign()
                ->references('id')
                ->on('products');

            // required
            $table->unsignedSmallInteger('status_id')
                ->index()
                ->foreign()
                ->references('id')
                ->on('process_statuses');

            // auto
            $table->date('responsible_person_update_date');

            // required
            $table->unsignedSmallInteger('responsible_person_id')
                ->index()
                ->foreign()
                ->references('id')
                ->on('process_responsible_people');

            // Stage 1 (ВП)
            // required and immutable after stage 1
            $table->unsignedSmallInteger('country_id')
                ->index()
                ->foreign()
                ->references('id')
                ->on('countries');

            // Stage 2 (ПО)
            $table->unsignedInteger('forecast_year_1')->nullable(); // required
            $table->unsignedInteger('forecast_year_2')->nullable(); // required
            $table->unsignedInteger('forecast_year_3')->nullable(); // required
            $table->date('forecast_year_1_update_date')->nullable(); // auto

            $table->string('dossier_status')->nullable(); // nullable until the end
            $table->string('clinical_trial_year')->nullable(); // nullable until the end
            $table->string('clinical_trial_ich_country')->nullable(); // nullable until the end
            $table->string('down_payment_1')->nullable(); // nullable until the end
            $table->string('down_payment_2')->nullable(); // nullable until the end
            $table->string('down_payment_condition')->nullable(); // nullable until the end

            // Stage 3 (АЦ)
            // required
            $table->unsignedSmallInteger('currency_id')
                ->nullable()
                ->index()
                ->foreign()
                ->references('id')
                ->on('currencies');

            $table->decimal('manufacturer_first_offered_price', 8, 2)->nullable(); // required
            $table->decimal('manufacturer_followed_offered_price', 8, 2)->nullable(); // required
            $table->decimal('our_first_offered_price', 8, 2)->nullable(); // required
            $table->decimal('our_followed_offered_price', 8, 2)->nullable(); // required

            // nullable at stages (3, 4) and became required at stage 5
            $table->unsignedSmallInteger('marketing_authorization_holder_id')
                ->nullable()
                ->index()
                ->foreign()
                ->references('id')
                ->on('marketing_authorization_holders');

            $table->string('trademark_en')->nullable(); // nullable at stages (3, 4) and became required at stage 5
            $table->string('trademark_ru')->nullable(); // nullable at stages (3, 4) and became required at stage 5

            // Stage 4 (СЦ)
            $table->decimal('agreed_price', 8, 2)->nullable(); // required
            $table->decimal('increased_price', 8, 2)->nullable(); // nullable
            $table->date('increased_price_date')->nullable(); // auto

            // Order part
            $table->timestamp('readiness_for_order_date')->nullable(); // auto

            // Timestamps
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('processes');
    }
};
