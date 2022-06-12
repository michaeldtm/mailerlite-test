<?php

use App\Models\Subscriber;
use Illuminate\Testing\Fluent\AssertableJson;

it('should retrieve all fields on database', function () {
    Subscriber::factory()->count(5)->create();
    $this->getJson('/api/subscribers')
        ->assertOk()
        ->assertJson(fn(AssertableJson $json) =>
            $json->has('data', 5)->etc()
        );
});

it('should create a field successfully', function () {
    $subscriber = Subscriber::factory()->make();
    $this->postJson('/api/subscribers', $subscriber->toArray())
        ->assertOk()
        ->assertJson(fn(AssertableJson $json) =>
            $json->has('data', fn($json) =>
                $json->where('email_address', $subscriber->email_address)
                    ->where('name', $subscriber->name)
                    ->where('state', $subscriber->state)
                    ->etc()
                )
            );
    $this->assertDatabaseHas('subscribers', $subscriber->toArray());
});

it('should fail creating a subscriber when validation fails', function ($email_address, $name, $state) {
    Subscriber::factory()->create(['email_address' => 'me@gmail.com']);
    $payload = [$email_address, $name, $state];
    $this->postJson('/api/subscribers', $payload)
        ->assertStatus(422);
    $this->assertDatabaseMissing('subscribers', $payload);
})->with([
    ['email_address' => '', 'name' => 'Michael Torres', 'state' => 'active'],
    ['email_address' => 'me@gmail.com', 'name' => 'Michael Torres', 'state' => 'active'],
    ['email_address' => 'me@michaeltorres.dev', 'name' => 'Michael Torres', 'state' => 'active'],
    ['email_address' => 'some@gmail.com', 'name' => '', 'state' => 'active'],
    ['email_address' => 'some@gmail.com', 'name' => 'Michael Torres', 'state' => ''],
    ['email_address' => 'some@gmail.com', 'name' => 'Michael Torres', 'state' => 'draft'],
]);

it('should show a subscriber successfully', function () {
    $subscriber = Subscriber::factory()->create();
    $this->getJson('/api/subscribers/' . $subscriber->id)
        ->assertOk()
        ->assertJson(fn(AssertableJson $json) =>
            $json->has('data', fn($json) =>
                $json->where('email_address', $subscriber->email_address)
                    ->where('name', $subscriber->name)
                    ->where('state', $subscriber->state)
                    ->etc()
                )
            );
});

it('should fail if subscriber does not exists on database', function () {
    $response = $this->getJson('/api/subscribers/' . 1);
    $response->assertNotFound();
});

it('should update a subscriber successfully', function () {
    $subscriber = Subscriber::factory()->create();
    $data = ['email_address' => $subscriber->email_address, 'name' => 'Michael Torres', 'state' => 'unsubscribed'];
    $this->putJson('/api/subscribers/' . $subscriber->id, $data)
        ->assertOk()
        ->assertJson(fn(AssertableJson $json) =>
            $json->has('data', fn($json) =>
                $json->where('email_address', $data['email_address'])
                    ->where('name', $data['name'])
                    ->where('state', $data['state'])
                    ->etc()
                )
            );
    $this->assertDatabaseHas('subscribers', $data);
});

it('should fail update subscriber when validation fails', function ($email_address, $name, $state) {
    $subscriber = Subscriber::factory()->create(['email_address' => 'me@gmail.com']);
    $payload = [$email_address, $name, $state];
    $this->putJson('/api/subscribers/' . $subscriber->id, $payload)
        ->assertStatus(422);
    $this->assertDatabaseMissing('subscribers', $payload);
})->with([
    ['email_address' => '', 'name' => 'Michael Torres', 'state' => 'active'],
    ['email_address' => 'me@gmail.com', 'name' => '', 'state' => 'active'],
    ['email_address' => 'some@gmail.com', 'name' => 'Michael Torres', 'state' => ''],
    ['email_address' => 'some@gmail.com', 'name' => 'Michael Torres', 'state' => 'draft'],
]);

it('should deleted a field successfully', function () {
    $field = Subscriber::factory()->create();
    $this->deleteJson('/api/subscribers/' . $field->id)
        ->assertOk()
        ->assertJson([
            'deleted' => true
        ]);
    $this->assertDatabaseMissing('fields', ['id' => $field->id]);
});

it('should fails when trying to deleted a non-existing subscriber', function () {
    $this->deleteJson('/api/subscribers/1')
        ->assertNotFound();
});
