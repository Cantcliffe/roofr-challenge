<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use App\Http\Requests\UnparkRequest;
use Illuminate\Support\Facades\Log;

class UnparkController extends Controller
{
    /**
     * @var \App\Models\Vehicle
     */
    protected $vehicle;

    /**
     * @var \Illuminate\Contracts\Routing\ResponseFactory
     */
    protected $response;

    /**
     * Create a new controller instance.
     *
     * @param \App\Models\Vehicle                           $vehicle
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     *
     * @return void
     */
    public function __construct(
        Vehicle $vehicle,
        ResponseFactory $response
    )
    {
        $this->vehicle = $vehicle;
        $this->response = $response;
    }

    /**
     * Unpark a vehicle.
     *
     * @param \App\Http\Requests\UnparkRequest $request
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     *
     */
    public function store(UnparkRequest $request)
    {
        $vehicle = $this->vehicle->where('license_plate', '=', $request->license_plate)->first();

        if (!$vehicle) {
            return $this->response->json(["Message" => "Could not find vehicle with license plate: {$request->license_plate}"], 404);
        }

        $vehicle->spots()->detach();

        return $this->response->json(["Message" => "Vehicle unparked"], 200);
    }

}
