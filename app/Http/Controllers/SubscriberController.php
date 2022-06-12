<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscriberRequest;
use App\Http\Requests\UpdateSubscriberRequest;
use App\Models\Subscriber;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\JsonResponse;

class SubscriberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Paginator
     */
    public function index(): Paginator
    {
        return Subscriber::query()->simplePaginate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreSubscriberRequest $request
     * @return JsonResponse
     ß*/
    public function store(StoreSubscriberRequest $request): JsonResponse
    {
        $subscriber = Subscriber::query()->create($request->validated());

        return response()->json(['data' => $subscriber]);
    }

    /**
     * Display the specified resource.
     *
     * @param Subscriber $subscriber
     * @return JsonResponse
     */
    public function show(Subscriber $subscriber): JsonResponse
    {
        return response()->json(['data' => $subscriber]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateSubscriberRequest $request
     * @param Subscriber $subscriber
     * @return JsonResponse
     */
    public function update(UpdateSubscriberRequest $request, Subscriber $subscriber): JsonResponse
    {
        $subscriber->update($request->validated());

        return response()->json(['data' => $subscriber]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Subscriber $subscriber
     * @return JsonResponse
     */
    public function destroy(Subscriber $subscriber): JsonResponse
    {
        $subscriber->delete();

        return response()->json(['deleted' => true]);
    }
}
