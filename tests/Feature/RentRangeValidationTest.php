<?php


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
    $response = $this->postJson('/rent-range', [
        'zip_code' => '75001',
        'room_number' => 2,
        'construction_period' => 'Apres 1990',
        'furnished' => true
    ]);

    $response->assertStatus(200);

});
