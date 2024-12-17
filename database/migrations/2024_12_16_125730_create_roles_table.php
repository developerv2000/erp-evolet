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
        Schema::create('roles', function (Blueprint $table) {
            $table->unsignedSmallInteger('id')->autoIncrement();
            $table->string('name')->unique();

            $table->unsignedSmallInteger('department_id') // Department admins can attach only global and department roles to users.
                ->foreign()
                ->references('id')
                ->on('departments')
                ->nullable();

            $table->boolean('global')->default(false); // Global roles can be attached to any department users.
            $table->string('description')->nullable();
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->unsignedSmallInteger('role_id')
                ->foreign()
                ->references('id')
                ->on('roles');

            $table->unsignedSmallInteger('user_id')
                ->foreign()
                ->references('id')
                ->on('users');

            $table->primary(['role_id', 'user_id']);
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
        Schema::dropIfExists('role_user');
    }
};
