<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Spot;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use App\Http\Requests\ParkRequest;
use Illuminate\Support\Facades\Log;

class ParkController extends Controller
{
    /**
     * @var \App\Models\Vehicle
     */
    protected $vehicle;

    /**
     * @var \App\Models\Spot
     */
    protected $spot;

    /**
     * @var \Illuminate\Contracts\Routing\ResponseFactory
     */
    protected $response;

    /**
     * Create a new controller instance.
     *
     * @param \App\Models\Vehicle                           $vehicle
     * @param \App\Models\Spot                              $spot
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     *
     * @return void
     */
    public function __construct(
        Vehicle $vehicle,
        Spot $spot,
        ResponseFactory $response
    )
    {
        $this->vehicle = $vehicle;
        $this->spot = $spot;
        $this->response = $response;
    }

    /**
     * Park a vehicle.
     *
     * @param \App\Http\Requests\ParkRequest $request
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     *
     */
    public function store(ParkRequest $request)
    {
        $vehicle = $this->vehicle->where('license_plate', '=', $request->license_plate)->first();

        if (!$vehicle) {
            $vehicle = $this->vehicle->create(['type' => $request->type, 'license_plate' => $request->license_plate]);
        }

        $spot = $this->spot->find($request->spot_id);

        if ($spot->canPark($vehicle)) {
            $vehicle->park($spot);

            return $this->response->json(["Message" => "Vehicle Parked"], 201);
        }

        return $this->response->json(["Message" => "Cannot park vehicle. Not enough space available!"], 400);
    }

}
