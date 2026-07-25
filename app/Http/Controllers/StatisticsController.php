<?php

namespace App\Http\Controllers;
use App\Models\Car;
use App\Models\Reservation;
use App\Models\Driver;
use App\Models\Invoice;
use App\Models\User;
use App\Http\Resources\StatisticsResource;


class StatisticsController extends Controller
{
    public function index()
    {
        $data = (object) [
            'totalCars' => Car::query()->count(),
            'totalDrivers' => Driver::query()->count(),
            'totalReservations' => Reservation::query()->count(),
            'totalClients' => User::query()->where('role','client')->count(),

            'activeReservations' => Reservation::query()->where('status', 'en cours')->count(),
            'availableDrivers'=> Driver::query()->where('status', 'disponible')->count(),
            'unAvailableDrivers' => Driver::query()->where('status', 'indisponible')->count(),
            'busyDrivers' => Driver::query()->where('status', 'affecté')->count(),
            'monthlyRevenue'=> Invoice::query()->whereMonth('created_at', now()->month)
                                           ->whereYear('created_at', now()->year)
                                           ->sum('amount'),

            'carsAvailable' => Car::query()->where('status', 'disponible')->count(),
            'carsUnAvailable' => Car::query()->where('status', 'indisponible')->count(),
            'carsRented'=> Car::query()->where('status', 'en location')->count(),
            'carsBroken'=> Car::query()->where('status', 'en panne')->count(),

            'reservationActivity' => Reservation::query()->selectRaw('DAYNAME(created_at) as day, COUNT(*) as count')
                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->groupBy('day')
                ->orderBy('day')
                ->get(),
        ];

        return new StatisticsResource($data);
    }
}
