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
        // table columns according to the csv  file logement-encadrements (first excel line)
        Schema::create('logement_encadrements', function (Blueprint $table) {
            $table->id();
            //nullable for safe seeding
            $table->string("geographic_sector")->nullable();
            $table->string("street_number")->nullable();
            $table->string("street_name")->nullable();
            $table->integer("room_number")->nullable();
            $table->string("construction_period")->nullable();
            $table->enum('furnished_type', ['furnished', 'unfurnished'])->nullable();
            $table->float("reference")->nullable();
            $table->float("major_reference")->nullable();
            $table->float("minor_reference")->nullable();
            $table->year("year")->nullable();
            $table->string("city")->nullable();
            $table->string("INSEE_code")->nullable();
            $table->json("geographic_shape")->nullable();
            $table->string("geographic_point_2d")->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logement_encadrements');
    }
};
