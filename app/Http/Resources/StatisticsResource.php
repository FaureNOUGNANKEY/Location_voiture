<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatisticsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'totals' => [
                'cars'         => $this->totalCars,
                'activeReservations' => $this->activeReservations,
                'availableDrivers'      => $this->availableDrivers,
                'monthlyRevenue'      => $this->monthlyRevenue,
            ],
            'carsStatus' => [
                'available'   => $this->carsAvailable,
                'unAvailable' => $this->carsUnAvailable,
                'rented'      => $this->carsRented,
                'broken'      => $this->carsBroken,
            ],
            'reservationActivity' => $this->reservationActivity,
        ];
    }
}
