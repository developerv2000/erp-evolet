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
        Schema::create('countries', function (Blueprint $table) {
            $table->unsignedSmallInteger('id')->autoIncrement();
            $table->string('name')->unique();
            $table->string('code')->unique();
            $table->unsignedMediumInteger('usage_count')->default(0);
            $table->unsignedMediumInteger('database_processes_count')->default(0);
        });

        Schema::create('clinical_trial_country_process', function (Blueprint $table) {
            $table->unsignedInteger('process_id')
                ->foreign()
                ->references('id')
                ->on('processes');

            $table->unsignedSmallInteger('country_id')
                ->foreign()
                ->references('id')
                ->on('countries');

            $table->primary(['process_id', 'country_id']);
        });

        Schema::create('responsible_country_user', function (Blueprint $table) {
            $table->unsignedSmallInteger('country_id')
                ->foreign()
                ->references('id')
                ->on('country_codes');

            $table->unsignedSmallInteger('user_id')
                ->foreign()
                ->references('id')
                ->on('users');

            $table->primary(['country_id', 'user_id']);
        });

        Schema::create('additional_search_country_product_search', function (Blueprint $table) {
            $table->unsignedInteger('country_id')
                ->foreign()
                ->references('id')
                ->on('countries');

            $table->unsignedSmallInteger('product_search_id')
                ->foreign()
                ->references('id')
                ->on('product_searches');

            $table->primary(['country_id', 'product_search_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
        Schema::dropIfExists('clinical_trial_country_process');
    }
};
