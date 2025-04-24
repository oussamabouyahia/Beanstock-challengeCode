<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('logement_encadrements', function (Blueprint $table) {

            $table->unsignedBigInteger('quartier_id')->nullable()->after('goegraphic_point_2d');
            $table
                ->foreign('quartier_id')
                ->references('id')
                ->on('quartiers_paris')
                ->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logement_encadrements', function (Blueprint $table) {
            $table->dropForeign(['quartier_id']);
            $table->dropColumn('quartier_id');
        });
    }
};
