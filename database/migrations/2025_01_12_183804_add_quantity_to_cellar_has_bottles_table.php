<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('cellar_has_bottles', function (Blueprint $table) {
            if (!Schema::hasColumn('cellar_has_bottles', 'quantity')) {
                $table->integer('quantity')->after('bottle_id');
            }
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cellar_has_bottles', function (Blueprint $table) {
            $table->dropColumn('quantity');
        });
    }
};
