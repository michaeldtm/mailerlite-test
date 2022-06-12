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
    $field = Field::factory()->make();
    $response = $this->postJson('/api/fields', $field->toArray());
    $response->assertOk()
        ->assertJson(fn(AssertableJson $json) =>
            $json->has('data', fn($json) =>
                $json->where('title', $field->title)
                    ->where('type', $field->type)
                    ->where('subscriber_id', $field->subscriber_id)
                    ->etc()
                )
            );
    $this->assertDatabaseHas('fields', $field->toArray());
});

it('should fail creating a field when validation fails', function ($expect, $data) {
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
                ->where('subscriber_id', $field->subscriber_id)
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
    $data = ['title' => 'Other title', 'type' => 'string', 'subscriber_id' => $field->subscriber_id];
    $response = $this->putJson('/api/fields/' . $field->id, $data);
    $response->assertOk()
        ->assertJson(fn(AssertableJson $json) =>
            $json->has('data', fn($json) =>
                $json->where('title', $data['title'])
                    ->where('type', $data['type'])
                    ->where('subscriber_id', $data['subscriber_id'])
                    ->etc()
                )
            );
    $this->assertDatabaseHas('fields', $data);
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
