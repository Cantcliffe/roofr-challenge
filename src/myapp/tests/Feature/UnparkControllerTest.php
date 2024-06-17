<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Vehicle;
use App\Models\Spot;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UnparkControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var string
     */
    protected $base = 'api/unpark';

    public function testCanUnparkVehicle()
    {
        $spotA = Spot::factory()->create([]);
        $vehicle = Vehicle::factory()->create([]);
        $spotA->vehicle()->attach($vehicle);

        $this->assertTrue($vehicle->spots->contains($spotA));

        $attributes = ['license_plate' => $vehicle->license_plate];

        $this->withoutExceptionHandling()
            ->post($this->base, $attributes)
            ->assertOk();

        $vehicle->refresh();

        $this->assertFalse($vehicle->spots->contains($spotA));
    }

    public function testCannotUnparkNonExistentVehicle()
    {
        $attributes = ['license_plate' => 'foo'];

        $this->withoutExceptionHandling()
            ->post($this->base, $attributes)
            ->assertNotFound();
    }

}
