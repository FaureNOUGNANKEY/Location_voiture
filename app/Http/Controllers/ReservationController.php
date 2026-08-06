<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use App\Models\Setting;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    // Liste des réservations
    public function index()
    {
        $reservations = Reservation::with(['user','car','driver','car.category','invoice'])->orderBy('created_at','desc')->paginate(15);
        return ReservationResource::collection($reservations);
    }

    // Créer une réservation
    // public function store(StoreReservationRequest $request)
    // {
    //     $reservation = Reservation::create($request->validated());

    //      // Créer la facture liée (les calculs se font dans Invoice::boot)
    //     $invoice = $reservation->invoice()->create([
    //         'driverAmount'    => $reservation->driverAmount,
    //         'reductionAmount' => $reservation->reductionAmount ?? 0,
    //         'status'          => 'En attente',
    //     ]);

    //     //Retourner la réponse API
    //     return response()->json([
    //         'reservation' => new ReservationResource($reservation),
    //         'invoice'     => $invoice
    //     ], 201);


       // return new ReservationResource($reservation);
    // }

    public function store(StoreReservationRequest $request)
{
    $data = $request->validated();
    $user = $request->user();

    // Si c'est un client, on force son user_id
    if ($user->role === 'client') {
        $data['user_id'] = $user->id;
    }

    // Si c'est un admin, on garde le user_id envoyé dans la requête
    $reservation = Reservation::create($data);

    $invoice = $reservation->invoice()->create([
        'driverAmount'    => $reservation->driverAmount,
        'reductionAmount' => $reservation->reductionAmount ?? 0,
        'status'          => 'En attente',
    ]);

    return response()->json([
        'reservation' => new ReservationResource($reservation),
        'invoice' => $invoice,
    ], 201);
}

    //Afficher une réservation
    public function show(Request $request, int $id)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non authentifié'], 401);
        }

        if ($user->role === 'admin') {
            // Admin : accès complet
            $reservation = Reservation::with(['user','car','driver','car.category','invoice'])
                ->findOrFail($id);
        } else {
            // Client : accès limité à ses réservations
            $reservation = Reservation::with(['user','car','driver','car.category','invoice'])
                ->where('id', $id)
                ->where('user_id', $user->id)
                ->firstOrFail();
        }

        return new ReservationResource($reservation);
    }


    // Mettre à jour une réservation
    public function update(StoreReservationRequest $request, int $id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update($request->validated());

        $invoice = $reservation->invoice;
        if ($invoice) {
            $invoice->update([
                'driverAmount'    => $reservation->driverAmount,
                'reductionAmount' => $reservation->reductionAmount ?? 0,
                'status'          => $invoice->status ?? "En attente",
        ]);
    }
        return response()->json([
            'reservation' => new ReservationResource($reservation),
            'invoice'     => $invoice
        ], 201);
    }

    // Supprimer une réservation
    public function destroy(int $id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();
        return response()->json(['message' => 'Réservation supprimée avec succès'],200);
    }


    public function estimate(StoreReservationRequest $request)
    {
        $data = $request->validated();

        $startDate = \Carbon\Carbon::parse($data['dateStart']);
        $endDate   = \Carbon\Carbon::parse($data['dateBack']);
        $days = $startDate->diffInDays($endDate);
        $days = round($days);

        $car = \App\Models\Car::findOrFail($data['car_id']);
        $dayAmount = $car->dayAmount ?? 0;

        $baseAmount = $days * $dayAmount;

        $reductionRate = \App\Models\Setting::get('reductionRate', 0);
        $tvaRate       = \App\Models\Setting::get('tvaRate', 0);
        $driverDailyRate = Setting::get('driverDailyRate', 0);

        $reductionAmount = $baseAmount * $reductionRate;

        if ($data['type'] === 'leasing') {
            $driverAmount    = $driverDailyRate * $days ;
        }else{
            $driverAmount = 0;
        }

        $netPrice   = $baseAmount - $reductionAmount + $driverAmount;
        $tvaAmount  = $netPrice * $tvaRate;
        $totalAmount = $netPrice + $tvaAmount;

        return response()->json([
            'days'            => $days,
            'driverDailyRate'=>round($driverDailyRate,2),
            'carAmount'       => round($baseAmount, 2),
            'reductionAmount' => round($reductionAmount, 2),
            'driverAmount'    => round($driverAmount, 2),
            'tvaAmount'       => round($tvaAmount, 2),
            'totalAmount'     => round($totalAmount, 2),
        ]);
    }

    public function myReservations(Request $request)
    {
        $user = $request->user();

        // Récupérer toutes les réservations de l’utilisateur connecté
        $reservations = Reservation::with(['car','driver','user','invoice','car.category'])
            ->where('user_id', $user->id)
            ->orderBy('dateStart', 'asc')
            ->get();

        // Retourner via Resource
        return ReservationResource::collection($reservations);
    }

    public function cancel(Request $request, int $id)
    {
        $reservation = Reservation::findOrFail($id);

        // Vérifier que l'utilisateur connecté est bien propriétaire
        $user = $request->user();
        if (!$user || $reservation->user_id !== $user->id) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $reservation->status = 'annulée';
        $reservation->save();

        return new ReservationResource($reservation);
    }
}
