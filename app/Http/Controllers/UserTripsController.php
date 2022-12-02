<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserTripRequest;
use App\Http\Resources\UserTripResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Models\UserTrip;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserTripsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return UserTripResource::collection(
            UserTrip::where('user_id', Auth::user()->id)->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserTripRequest $request
     * @return Response|UserTripResource
     */
    public function store(StoreUserTripRequest $request): Response|UserTripResource
    {
        $record = UserTrip::create([
            'user_id' => Auth::user()->id,
            'trip_id' => $request->get('trip_id'),
            'status' => $request->get('status'),
        ]);

        return new UserTripResource($record);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param UserTrip $record
     * @return Response
     */
    public function destroy(UserTrip $record): Response
    {
        $record->delete();
        return response(null, \Symfony\Component\HttpFoundation\Response::HTTP_NO_CONTENT);
    }
}
