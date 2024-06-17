<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Vehicle;
use App\Models\Spot;
use App\Contracts\VehicleTypes;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class VehicleTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test model creation
     */
    public function testCanCreateModel(): void
    {
        $vehicle = Vehicle::factory()->create(['type' => VehicleTypes::TYPE_CAR]);
        $this->assertEquals($vehicle::class, Vehicle::class);
    }

    /**
     * Test spots function
     */
    public function testSpots(): void
    {
        $vehicle = Vehicle::factory()->create(['type' => VehicleTypes::TYPE_CAR]);
        $spotA = Spot::factory()->create([]);
        $spotB = Spot::factory()->create([]);
        $vehicle->spots()->attach($spotA);
        $vehicle->spots()->attach($spotB);
        $this->assertTrue($vehicle->spots->contains($spotA));
        $this->assertTrue($vehicle->spots->contains($spotB));
    }

    /**
     * Test park function
     */
    public function testParkSingleSpaceVehicle(): void
    {
        $vehicle = Vehicle::factory()->create(['type' => VehicleTypes::TYPE_CAR]);
        $spotA = Spot::factory()->create([]);
        $vehicle->park($spotA);
        $this->assertTrue($vehicle->spots->contains($spotA));
    }

    /**
     * Test park function
     */
    public function testParkVan(): void
    {
        $vehicle = Vehicle::factory()->create(['type' => VehicleTypes::TYPE_VAN]);
        $spotA = Spot::factory()->create([]);
        $spotB = Spot::factory()->create([]);
        $spotC = Spot::factory()->create([]);

        $vehicle->park($spotC);
        $this->assertTrue($vehicle->spots->contains($spotA));
        $this->assertTrue($vehicle->spots->contains($spotB));
        $this->assertTrue($vehicle->spots->contains($spotC));
    }
}

