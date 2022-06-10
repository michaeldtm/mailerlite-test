<?php

namespace App\Http\Controllers;

use App\Http\Requests\FieldRequest;
use App\Http\Requests\UpdateFieldRequest;
use App\Models\Field;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\JsonResponse;

class FieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Paginator
     */
    public function index(): Paginator
    {
        return Field::query()->simplePaginate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param FieldRequest $request
     * @return JsonResponse
     */
    public function store(FieldRequest $request): JsonResponse
    {
        $field = Field::query()->create($request->validated());

        return response()->json(['data' => $field]);
    }

    /**
     * Display the specified resource.
     *
     * @param Field $field
     * @return JsonResponse
     */
    public function show(Field $field): JsonResponse
    {
        return response()->json(['data' => $field]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param FieldRequest $request
     * @param Field $field
     * @return JsonResponse
     */
    public function update(FieldRequest $request, Field $field): JsonResponse
    {
        $field->update($request->validated());

        return response()->json(['data' => $field]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Field $field
     * @return JsonResponse
     */
    public function destroy(Field $field): JsonResponse
    {
        $field->delete();

        return response()->json(['deleted' => true]);
    }
}
