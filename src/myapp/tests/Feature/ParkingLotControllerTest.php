<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Spot;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ParkingLotControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var string
     */
    protected $base = 'api/parking-lot';

    public function testCanGetSpotInfo()
    {
        $spotA = Spot::factory()->create([]);
        $spotB = Spot::factory()->create([]);
        $vehicle = Vehicle::factory()->create([]);
        $spotB->vehicle()->attach($vehicle);

        $this->withoutExceptionHandling()
            ->get($this->base)
            ->assertOk()
            ->assertJson([
                'emptySpots'    => [$spotA->id],
                'occupiedSpots' => [$spotB->id],
            ]);

    }

    public function testCanCreateSpots()
    {
        $this->assertDatabaseCount('spots', 0);

        $attributes = ['numberOfSpots' => 5];

        $this->withoutExceptionHandling()
            ->post($this->base, $attributes)
            ->assertOk();

        $this->assertDatabaseCount('spots', 5);
    }
}
