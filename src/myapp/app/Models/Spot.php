<?php

namespace App\Models;

use App\Models\Vehicle;
use App\Contracts\VehicleTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Spot extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'created_at',
        'updated_at',
    ];

    /**
     * Returns the vehicle currently in the spot, if applicable.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function vehicle()
    {
        return $this->belongsToMany(Vehicle::class);
    }

    /**
     * Returns whether spot is empty
     *
     * @return Boolean
     */
    public function isEmpty()
    {
        return $this->vehicle->isEmpty();
    }

    /**
     * Retrieve a spot's lot
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lot()
    {
        return $this->belongsTo(Lot::class, 'vendor_id');
    }

    /**
     * Returns the spot to the left of the current spot
     *
     * @return \App\Models\Spot|null
     */
    public function leftAdjacentSpot()
    {
        return Spot::find($this->id - 1);
    }

    /**
     * Returns the spot to the right of the current spot
     *
     * @return \App\Models\Spot|null
     */
    public function rightAdjacentSpot()
    {
        return Spot::find($this->id + 1);
    }

    /**
     * Whether a specific vehicle can park in the current spot
     *
     * @return Boolean
     */
    public function canPark(Vehicle $vehicle)
    {
        switch ($vehicle->type) {
            case VehicleTypes::TYPE_CAR:
            case VehicleTypes::TYPE_MOTORCYCLE:
                return $this->isEmpty();
            case VehicleTypes::TYPE_VAN:
                if ($this->isEmpty()) {
                    if ($this->leftAdjacentSpot()?->isEmpty() && $this->leftAdjacentSpot()?->leftAdjacentSpot()?->isEmpty()) {
                        return true;
                    }
                    if ($this->leftAdjacentSpot()?->isEmpty() && $this->rightAdjacentSpot()?->isEmpty()) {
                        return true;
                    }
                    if ($this->rightAdjacentSpot()?->isEmpty() && $this->rightAdjacentSpot()?->rightAdjacentSpot()?->isEmpty()) {
                        return true;
                    }
                }

                return false;
        }
    }

}
