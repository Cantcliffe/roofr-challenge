<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Contracts\VehicleTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Vehicle extends Model implements VehicleTypes
{
    use HasFactory;

    /**
     * The attributes that are mass attachable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'license_plate',
    ];

    public function spots()
    {
        return $this->belongsToMany(Spot::class);
    }

    public function park(Spot $spot)
    {
        switch ($this->type) {
            case VehicleTypes::TYPE_CAR:
            case VehicleTypes::TYPE_MOTORCYCLE:
                $this->spots()->attach($spot);
                break;
            case VehicleTypes::TYPE_VAN:
                if ($spot->isEmpty()) {

                    if ($spot->leftAdjacentSpot()?->isEmpty() && $spot->leftAdjacentSpot()?->leftAdjacentSpot()?->isEmpty()) {
                        $this->spots()->attach($spot);
                        $this->spots()->attach($spot->leftAdjacentSpot());
                        $this->spots()->attach($spot->leftAdjacentSpot()->leftAdjacentSpot());
                        break;
                    }

                    if ($spot->leftAdjacentSpot()?->isEmpty() && $spot->rightAdjacentSpot()?->isEmpty()) {
                        $this->spots()->attach($spot);
                        $this->spots()->attach($spot->leftAdjacentSpot());
                        $this->spots()->attach($spot->rightAdjacentSpot());
                        break;
                    }
                    if ($spot->rightAdjacentSpot()?->isEmpty() && $spot->rightAdjacentSpot()?->rightAdjacentSpot()?->isEmpty()) {
                        $this->spots()->attach($spot);
                        $this->spots()->attach($spot->rightAdjacentSpot());
                        $this->spots()->attach($spot->rightAdjacentSpot()->rightAdjacentSpot());
                        break;
                    }
                }
                break;
        }
    }

    public function scopeCars($query)
    {
        return $query->where('type', $this::TYPE_CAR);
    }

    public function scopeMotorcyles($query)
    {
        return $query->where('type', $this::TYPE_MOTORCYCLE);
    }

    public function scopeVans($query)
    {
        return $query->where('type', $this::TYPE_VAN);
    }
}
