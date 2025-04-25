<?php

use App\Models\QuartiersParis;
use App\Models\LogementEncadrement;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('a quartier can have many logementEncadrements', function () {
    // Create quartier
    $quartier = QuartiersParis::factory()->create();

    // Create logement records related to that quartier
    LogementEncadrement::factory()->count(3)->create([
        'quartier_id' => $quartier->id,
    ]);

    // Reload the relationship
    $quartier->refresh();

    // Assert relationship returns those logements
    expect($quartier->logementEncadrements)->toHaveCount(3);
});
