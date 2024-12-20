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
        Schema::create('zones', function (Blueprint $table) {
            $table->unsignedSmallInteger('id')->autoIncrement();
            $table->string('name')->unique();
        });

        Schema::create('manufacturer_zone', function (Blueprint $table) {
            $table->unsignedInteger('manufacturer_id')
                ->foreign()
                ->references('id')
                ->on('manufacturers');

            $table->unsignedSmallInteger('zone_id')
                ->foreign()
                ->references('id')
                ->on('zones');

            $table->primary(['manufacturer_id', 'zone_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zones');
        Schema::dropIfExists('manufacturer_zone');
    }
};
