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
        Schema::create('atxes', function (Blueprint $table) {
            $table->unsignedMediumInteger('id')->autoIncrement();
            $table->string('name')->index();
            $table->string('short_name')
                ->nullable()
                ->index();

            $table->unsignedMediumInteger('inn_id')
                ->index()
                ->foreign()
                ->references('id')
                ->on('inns');

            $table->unsignedSmallInteger('form_id')
                ->index()
                ->foreign()
                ->references('id')
                ->on('product_forms');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atxes');
    }
};
