<?php

use App\Http\Requests\FieldRequest;

beforeEach(function () {
    $this->request = new FieldRequest();
    $this->rules = $this->request->rules();
});

it('authorizes', function () {
    expect($this->request->authorize())->toBeTrue();
});

it('validates correctly', function ($expect, $data) {
    expect($expect)->toBe(validate($data, $this->rules));
})->with('good_fields')->with('wrong_fields');
