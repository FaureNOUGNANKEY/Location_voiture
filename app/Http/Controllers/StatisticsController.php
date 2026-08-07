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
            'totalClients' => User::query()->where('role', 'client')->count(),
            'totalAdmins' => User::query()->where('role', 'admin')->count(),
            'activeReservations' => Reservation::all()
                ->filter(fn($r) => $r->computed_status === 'En cours')
                ->count(),
            'availableDrivers' => Driver::query()->where('status', 'Disponible')->count(),
            'unAvailableDrivers' => Driver::query()->where('status', 'Indisponible')->count(),
            'busyDrivers' => Driver::query()->where('status', 'Affecté')->count(),
            'monthlyRevenue' => Invoice::query()->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount'),

            'carsAvailable' => Car::query()->where('status', 'Disponible')->count(),
            'carsUnAvailable' => Car::query()->where('status', 'Indisponible')->count(),
            'carsRented' => Car::query()->where('status', 'Louée')->count(),
            'carsBroken' => Car::query()->where('status', 'En panne')->count(),
            'carsInRepair' => Car::query()->where('status', 'En réparation')->count(),

            'waitingForDriver' => Reservation::query()
                ->whereNull('driver_id')
                ->where('driverAmount', '>', 0)
                ->count(),

            'reservationActivity' => Reservation::query()
                ->selectRaw('DAYNAME(created_at) as day, COUNT(*) as count')
                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->where('status', '!=', 'Annulée') //exclure les annulées
                ->groupBy('day')
                ->orderBy('day')
                ->get(),


        ];

        return new StatisticsResource($data);
    }
}
