<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Vehicle;
use App\Models\Spot;
use App\Contracts\VehicleTypes;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ParkControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var string
     */
    protected $base = 'api/park';

    public function testCanParkVehicle()
    {
        $vehicle = Vehicle::factory()->create([]);
        $spotA = Spot::factory()->create([]);
        $attributes = ['license_plate' => $vehicle->license_plate, 'spot_id' => $spotA->id, 'type' => VehicleTypes::TYPE_CAR];

        $this->withoutExceptionHandling()
            ->post($this->base, $attributes)
            ->assertCreated();

        $this->assertTrue($vehicle->spots->contains($spotA));
    }

    public function testCanParkVehicleWhenVehicleNotInDb()
    {
        $spotA = Spot::factory()->create([]);
        $attributes = ['license_plate' => 'abc', 'spot_id' => $spotA->id, 'type' => VehicleTypes::TYPE_CAR];

        $this->assertDatabaseCount('vehicles', 0);
        $this->withoutExceptionHandling()
            ->post($this->base, $attributes)
            ->assertCreated();
        $this->assertDatabaseCount('vehicles', 1);

        $vehicle = Vehicle::where('license_plate', 'abc')->first();

        $this->assertTrue($vehicle->spots->contains($spotA));
    }

    public function testCannotParkVehicleWhenSpotTaken()
    {
        $spotA = Spot::factory()->create([]);
        $vehicle = Vehicle::factory()->create([]);
        $spotA->vehicle()->attach($vehicle);

        $attributes = ['license_plate' => 'abc', 'spot_id' => $spotA->id, 'type' => VehicleTypes::TYPE_CAR];

        $this->assertDatabaseCount('vehicles', 1);
        $this->withoutExceptionHandling()
            ->post($this->base, $attributes)
            ->assertBadRequest();

        $this->assertDatabaseCount('vehicles', 2);
    }

}
