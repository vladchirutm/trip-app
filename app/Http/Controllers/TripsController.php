<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexTripRequest;
use App\Http\Requests\StoreTripRequest;
use App\Http\Resources\TripResource;
use App\Models\Trip;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Str;


class TripsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param IndexTripRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(IndexTripRequest $request): AnonymousResourceCollection
    {
        $orderBy = $request->get('order_by', 'start_date');
        $orderDirection = $request->get('order_direction', 'asc');

        $tripQuery = Trip::query()
            ->when($request->get('title'), function(Builder $query, $filter){
                $query->where('title', 'like', "%$filter%");
            })
            ->when($request->get('location'), function(Builder $query, $filter){
                $query->where('location', 'like', "%$filter%");
            })
            ->when($request->get('start_date'), function(Builder $query, $filter){
                $query->where('start_date', '>=', $filter);
            })
            ->when($request->get('end_date'), function(Builder $query, $filter){
                $query->where('end_date', '<', $filter);
            })
            ->when($request->get('min_price'), function(Builder $query, $filter){
                $query->where('price', '>=', $filter);
            })
            ->when($request->get('max_price'), function(Builder $query, $filter){
                $query->where('price', '<', $filter);
            })
            ->when($orderBy, function(Builder $query, $column) use ($orderDirection){
                $query->orderBy($column, $orderDirection);
            });

        return TripResource::collection($tripQuery->get());
    }

    /**
     * @param Trip $trip
     * @return TripResource
     */
    public function show(Trip $trip): TripResource
    {
        return new TripResource($trip);
    }

    public function findBySlug(string $slug): AnonymousResourceCollection
    {
        return TripResource::collection(Trip::where('slug', '=', $slug)->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreTripRequest $request
     * @return Response|TripResource
     */
    public function store(StoreTripRequest $request): Response|TripResource
    {
        $title = $request->get('title');

        $trip = Trip::create([
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => $request->get('description'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'location' => $request->get('location'),
            'price' => $request->get('price')
        ]);
        return new TripResource($trip);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreTripRequest $request
     * @param Trip $trip
     * @return TripResource
     */
    public function update(StoreTripRequest $request, Trip $trip): TripResource
    {
        $trip->update($request->all());
        return new TripResource($trip);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Trip $trip
     * @return Response
     */
    public function destroy(Trip $trip): Response
    {
        $trip->delete();
        return response(null, \Symfony\Component\HttpFoundation\Response::HTTP_NO_CONTENT);
    }
}
