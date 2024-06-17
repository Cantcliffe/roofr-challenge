<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ParkingLotControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var string
     */
    protected $base = 'api/parking-lot';

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
