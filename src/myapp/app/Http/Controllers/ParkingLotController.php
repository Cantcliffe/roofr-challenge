<?php

namespace App\Http\Controllers;

use App\Models\Spot;

use Illuminate\Http\Request;
use Illuminate\Contracts\Routing\ResponseFactory;
use App\Http\Requests\ParkingLotRequest;

class ParkingLotController extends Controller
{
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
     * @param \App\Models\Spot                              $spot
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     *
     * @return void
     */
    public function __construct(
        Spot $spot,
        ResponseFactory $response
    )
    {
        $this->spot = $spot;
        $this->response = $response;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function parkingLotStatus()
    {
        $spotsWithVehicle = Spot::has('vehicle')->get()->pluck('id')->toArray();
        $spotsWithoutVehicle = Spot::doesntHave('vehicle')->get()->pluck('id')->toArray();

        return $this->response->json(["emptySpots" => $spotsWithoutVehicle, "occupiedSpots" => $spotsWithVehicle], 200);

    }

    /**
     * Add spots to the lot
     *
     * @param \App\Http\Requests\ParkingLotRequest $request
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     *
     */
    public function store(ParkingLotRequest $request)
    {
        foreach (range(1, $request->numberOfSpots) as $i) {
            $this->spot->create();

        }

        return $this->response->json(["Message" => "Added {$request->numberOfSpots} spots to the lot."], 200);
    }

}
