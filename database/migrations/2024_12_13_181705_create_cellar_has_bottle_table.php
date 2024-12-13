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
        Schema::create('cellar_has_bottles', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('cellar_id');
			$table->unsignedBigInteger('bottle_id');
			$table->foreign('cellar_id')->references('id')->on('cellars')->onDelete('cascade');
			$table->foreign('bottle_id')->references('id')->on('bottles')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cellier_a_bouteilles');
    }
};
