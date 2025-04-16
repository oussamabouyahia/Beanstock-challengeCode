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
        // columns according to first line of the CSV file quartiers_paris
        Schema::create('quartiers_paris', function (Blueprint $table) {
            $table->id();
            $table->string("N_SQ_QU")->nullable();
            $table->string("street_number")->nullable();
            $table->string("C_QUINSEE")->nullable()->index();
            $table->string("L_QU")->nullable();
            $table->string("C_AR")->nullable();
            $table->string("N_SQ_AR")->nullable();
            $table->double("perimetre",10,2)->nullable();
            $table->double("surface",10,2)->nullable();
            $table->string("geometry_X_Y")->nullable();
            $table->string("zip_code")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quartiers_paris');
    }
};
