<?php

use App\Models\Field;
use App\Models\Subscriber;
use Illuminate\Testing\Fluent\AssertableJson;

it('should retrieve all fields on database', function () {
    Field::factory()
        ->for(Subscriber::factory())
        ->count(5)
        ->create();
    $response = $this->getJson('/api/fields');
    $response->assertOk()
        ->assertJson(fn(AssertableJson $json) =>
            $json->has('data', 5)->etc()
        );
});

it('should create a field successfully', function () {
    $field = Field::factory()->for(Subscriber::factory())->make();
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
    $field = Field::factory()->for(Subscriber::factory())->create();
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
    $field = Field::factory()->for(Subscriber::factory())->create();
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

it('should fail update field when validation fails', function ($title, $type, $subscriber_id) {
    $field = Field::factory()->for(Subscriber::factory())->create();
    $payload = [$title, $type, $subscriber_id];
    $this->putJson('/api/fields/' . $field->id, $payload)
        ->assertStatus(422);
    $this->assertDatabaseMissing('subscribers', $payload);
})->with([
    ['title' => '', 'type' => 'string', 'subscriber_id' => 1],
    ['title' => 'Some title', 'type' => '', 'subscriber_id' => 1],
    ['title' => 'Some title', 'type' => 'datetime', 'subscriber_id' => 1],
    ['title' => 'Some title', 'type' => 'string', 'subscriber_id' => null],
    ['title' => 'Some title', 'type' => 'datetime', 'subscriber_id' => 2]
]);

it('should deleted a field successfully', function () {
    $field = Field::factory()->for(Subscriber::factory())->create();
    $response = $this->deleteJson('/api/fields/' . $field->id);
    $response->assertOk()
        ->assertJson([
            'deleted' => true
        ]);
    $this->assertDatabaseMissing('fields', ['id' => $field->id]);
});

it('should fails when trying to deleted a non-existing field', function () {
    $this->deleteJson('/api/fields/1')
        ->assertNotFound();
});
