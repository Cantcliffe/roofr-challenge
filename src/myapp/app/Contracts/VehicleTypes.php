<?php

namespace App\Contracts;

interface VehicleTypes
{
    public const TYPE_CAR        = 1;
    public const TYPE_MOTORCYCLE = 2;
    public const TYPE_VAN        = 3;

    public const ALLOWED_VEHICLE_TYPES = [self::TYPE_VAN, self::TYPE_MOTORCYCLE, self::TYPE_CAR];

}
