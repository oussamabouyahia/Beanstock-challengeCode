<?php

use App\Models\QuartiersParis;
use App\Models\LogementEncadrement;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('a quartier can have many logementEncadrements', function () {
    // Create a Quartier
    $quartier = QuartiersParis::factory()->create();

    // Create 3 logements belongs to the Quartier above
    $logements = LogementEncadrement::factory()->count(3)->create([
        'quartier_id' => $quartier->id,
    ]);

    // Assert relationship returns those logements
    $this->assertCount(3, $quartier->logementEncadrements);
    $this->assertTrue($quartier->logementEncadrements->contains($logements->first()));
});
