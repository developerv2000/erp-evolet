<?php

use App\Support\Helpers\GeneralHelper;
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
        Schema::create('mad_asps', function (Blueprint $table) {
            $table->unsignedSmallInteger('id')->autoIncrement();
            $table->unsignedSmallInteger('year')->unique();
        });

        Schema::create('mad_asp_country', function (Blueprint $table) {
            $table->unsignedSmallInteger('id')->autoIncrement(); // used for ordering

            $table->unsignedSmallInteger('mad_asp_id')
                ->foreign()
                ->references('id')
                ->on('mad_asps');

            $table->unsignedInteger('country_id')
                ->foreign()
                ->references('id')
                ->on('countries');

            $table->unique(['mad_asp_id', 'country_id']);
        });

        Schema::create('mad_asp_country_marketing_authorization_holder', function (Blueprint $table) {
            $table->unsignedSmallInteger('id')->autoIncrement(); // used for ordering

            $table->unsignedSmallInteger('mad_asp_id')
                ->foreign()
                ->references('id')
                ->on('mad_asps');

            $table->unsignedSmallInteger('country_id')
                ->foreign()
                ->references('id')
                ->on('countries');

            $table->unsignedSmallInteger('marketing_authorization_holder_id')
                ->foreign()
                ->references('id')
                ->on('marketing_authorization_holders');

            $table->unique(
                ['mad_asp_id', 'country_id', 'marketing_authorization_holder_id'],
                'unique_mad_asp_country_mah'
            );

            // Define Europe/India contract plans
            $months = GeneralHelper::collectCalendarMonths();

            foreach ($months as $month) {
                $table->unsignedSmallInteger($month['name'] . '_europe_contract_plan')->default(0);
                $table->unsignedSmallInteger($month['name'] . '_india_contract_plan')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mad_asps');
        Schema::dropIfExists('mad_asp_country');
        Schema::dropIfExists('mad_asp_country_marketing_authorization_holder');
    }
};
