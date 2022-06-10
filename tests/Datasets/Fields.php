<?php

dataset('good_fields', [
    ['expect' => true, 'data' => payload()]
]);

dataset('wrong_fields', [
    ['expect' => false, 'data' => getPayloadWithValue('title', '')],
    ['expect' => false, 'data' => getPayloadWithValue('type', 'random')]
]);

function getPayloadWithValue(string $key, string $value): array
{
    $payload = payload();
    $payload[$key] = $value;
    return $payload;
}

function payload(): array
{
    return ['title' => 'Some title', 'type' => 'number'];
}
