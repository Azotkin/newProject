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
        Schema::create('state_users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname', 100);
            $table->string('surname', 100);
            $table->string('lastname', 100);
            $table->tinyInteger('age');
            $table->enum('gender', ['мужской', 'женский']);
            $table->string('education');
            $table->string('city', 100);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('state_users');
    }
};
