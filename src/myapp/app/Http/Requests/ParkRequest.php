<?php

namespace App\Http\Requests;

use App\Contracts\VehicleTypes;
use Illuminate\Foundation\Http\FormRequest;

class ParkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'spot_id'       => 'required|integer|exists:spots,id',
            'type'          => 'required|integer|in:' . implode(',', VehicleTypes::ALLOWED_VEHICLE_TYPES),
            'license_plate' => 'required|string',
        ];
    }

}
