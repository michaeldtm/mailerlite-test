<?php

use App\Models\Field;
use Illuminate\Testing\Fluent\AssertableJson;

it('should retrieve all fields on database', function () {
    Field::factory()->count(5)->create();
    $response = $this->getJson('/api/fields');
    $response->assertOk()
        ->assertJson(fn(AssertableJson $json) =>
            $json->has('data', 5)->etc()
        );
});

it('should create a field successfully', function () {
    $response = $this->postJson('/api/fields', ['title' => 'Title', 'type' => 'string']);
    $response->assertOk()
        ->assertJson(fn(AssertableJson $json) =>
            $json->has('data', fn($json) =>
                $json->where('title', 'Title')
                    ->where('type', 'string')
                    ->etc()
                )
            );
    $this->assertDatabaseHas('fields', ['title' => 'Title', 'type' => 'string']);
});

it('should fail validation when creating a field', function ($expect, $data) {
    $response = $this->postJson('/api/fields', $data);
    $response->assertStatus(422);
    $this->assertDatabaseMissing('fields', $data);
})->with('wrong_fields');

it('should show a field successfully', function () {
    $field = Field::factory()->create();
    $response = $this->getJson('/api/fields/' . $field->id);
    $response->assertOk()
        ->assertJson(fn(AssertableJson $json) =>
        $json->has('data', fn($json) =>
            $json->where('title', $field->title)
                ->where('type', $field->type)
                ->etc()
            )
        );
});

it('should fail if field does not exists on database', function () {
    $response = $this->getJson('/api/fields/' . 1);
    $response->assertNotFound();
});

it('should update a field successfully', function () {
    $field = Field::factory()->create();
    $response = $this->putJson('/api/fields/' . $field->id, ['title' => 'Other title', 'type' => 'string']);
    $response->assertOk()
        ->assertJson(fn(AssertableJson $json) =>
            $json->has('data', fn($json) =>
            $json->where('title', 'Other title')
                    ->etc()
                )
            );
    $this->assertDatabaseHas('fields', ['title' => 'Other title', 'type' => 'string']);
});

it('should deleted a field successfully', function () {
    $field = Field::factory()->create();
    $response = $this->deleteJson('/api/fields/' . $field->id);
    $response->assertOk()
        ->assertJson([
            'deleted' => true
        ]);
    $this->assertDatabaseMissing('fields', ['id' => $field->id]);
});
