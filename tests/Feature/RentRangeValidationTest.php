<?php


use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class); // Reset DB before each test


test('validation fails if zip code and coordinates are missing', function () {

    $response = $this->postJson('/rent-range', [
        'room_number' => 2,
        'construction_period' => 'Apres 1990',
        'furnished' => true
    ]);


    $response->assertStatus(422);
    $response->assertJson([
        'status' => 'error',
        'message' => 'Validation failed',
    ]);
    $this->assertStringContainsString('zip code', $response['errors']);
});

test('test the custom validation rule for coordinates input', function () {
    $response = $this->postJson('/rent-range', [
        'coordinates' => 'notnumeric,notanumber',
        'room_number' => 2,
        'construction_period' => 'Apres 1990',
        'furnished' => true
    ]);

    $response->assertStatus(422);
    $response->assertJson([
        'status' => 'error',
        'message' => 'Validation failed',
    ]);

    $this->assertStringContainsString('coordinates', $response['errors']);
});



test('validation passes if zip code valid', function () {
    $quartier = \App\Models\QuartiersParis::factory()->create([
        'zip_code' => '75001',
        'geometry_X_Y' => '48.8566,2.3522',
    ]);

    \App\Models\LogementEncadrement::factory()->create([
        'quartier_id' => $quartier->id,
        'geographic_point_2d' => '48.8566,2.3522',
        'room_number' => 2,
        'construction_period' => 'Apres 1990',
        'furnished_type' => 'furnished',
        'major_reference' => 1200,
        'minor_reference' => 900,
        'reference' => 1050,
    ]);

    $response = $this->postJson('/rent-range', [
        'zip_code' => '75001',
        'room_number' => 2,
        'construction_period' => 'Apres 1990',
        'furnished' => true,
    ]);

    $response->assertStatus(200);
    $response->assertJsonFragment([
        'status' => 'success',
        'minimumRent' => 900,
        'maximumRent' => 1200,
        'averageRent' => 1050,
    ]);
});
