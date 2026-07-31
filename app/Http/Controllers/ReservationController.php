<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Reservation;

class ReservationController extends Controller
{
    // Liste des réservations
    public function index()
    {
        $reservations = Reservation::with(['user','car','driver','car.category','invoice'])->orderBy('created_at','desc')->paginate(15);
        return ReservationResource::collection($reservations);
    }

    // Créer une réservation
    public function store(StoreReservationRequest $request)
    {
        $reservation = Reservation::create($request->validated());

         // Créer la facture liée (les calculs se font dans Invoice::boot)
        $invoice = $reservation->invoice()->create([
            'driverAmount'    => $reservation->driverAmount,
            'reductionAmount' => $reservation->reductionAmount ?? 0,
            'status'          => 'En attente',
        ]);

        //Retourner la réponse API
        return response()->json([
            'reservation' => new ReservationResource($reservation),
            'invoice'     => $invoice
        ], 201);

       // return new ReservationResource($reservation);
    }

    // Afficher une réservation
    public function show(int $id)
    {
        $reservation = Reservation::with(['user','car','driver','car.category','invoice'])->findOrFail($id);
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
}
