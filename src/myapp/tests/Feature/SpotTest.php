<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Vehicle;
use App\Models\Spot;
use App\Contracts\VehicleTypes;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SpotTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test model creation
     */
    public function testCanCreateModel(): void
    {
        $spot = Spot::factory()->create();
        $this->assertEquals($spot::class, Spot::class);
    }

    /**
     * Test spots function
     */
    public function testVehicle(): void
    {
        $spot = Spot::factory()->create([]);
        $vehicle = Vehicle::factory()->create();

        $spot->vehicle()->attach($vehicle);

        $this->assertTrue($spot->vehicle->contains($vehicle));
    }

    /**
     * Test leftAdjacentSpot function
     */
    public function testLeftAdjacentSpot(): void
    {

        $spotA = Spot::factory()->create(['id' => 1]);
        $spotB = Spot::factory()->create(['id' => 2]);
        $spotC = Spot::factory()->create(['id' => 3]);

        $this->assertNull($spotA->leftAdjacentSpot());
        $this->assertEquals($spotB->leftAdjacentSpot()->id, $spotA->id);
    }

    /**
     * Test rightAdjacentSpot function
     */
    public function testRightAdjacentSpot(): void
    {

        $spotA = Spot::factory()->create(['id' => 1]);
        $spotB = Spot::factory()->create(['id' => 2]);

        $this->assertNull($spotB->rightAdjacentSpot());
        $this->assertEquals($spotA->rightAdjacentSpot()->id, $spotB->id);
    }

    /**
     * Test canPark function
     */
    public function testCanSingleSpaceVehicle(): void
    {
        $car = Vehicle::factory()->create(['type' => VehicleTypes::TYPE_CAR]);
        $motorcycle = Vehicle::factory()->create(['type' => VehicleTypes::TYPE_MOTORCYCLE]);

        $spotA = Spot::factory()->create();

        $this->assertTrue($spotA->canPark($car));
        $this->assertTrue($spotA->canPark($motorcycle));
    }

    /**
     * Test canPark function with Van
     */
    public function testCanParkVan(): void
    {
        $van = Vehicle::factory()->create(['type' => VehicleTypes::TYPE_VAN]);

        $spotA = Spot::factory()->create();
        $spotB = Spot::factory()->create();
        $spotC = Spot::factory()->create();

        $this->assertTrue($spotA->canPark($van));
        $this->assertTrue($spotB->canPark($van));
        $this->assertTrue($spotC->canPark($van));
    }
}

