<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('bottle_id');
            $table->timestamps();

            // Contraintes pour assurer les relations
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('bottle_id')->references('id')->on('bottles')->onDelete('cascade');
            $table->unique(['user_id', 'bottle_id']); // Un utilisateur ne peut pas ajouter deux fois la même bouteille
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
