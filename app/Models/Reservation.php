<?php

namespace App\Models;

use App\Http\Resources\InvoiceResource;
use Illuminate\Database\Eloquent\Model;
use App\Models\Setting;

/**
 * App\Models\Reservation
 *
 * @method static \Illuminate\Database\Eloquent\Builder query()
 * @method static \Illuminate\Database\Eloquent\Builder selectRaw(string $expression, array $bindings = [])
 * @method static \Illuminate\Database\Eloquent\Builder whereBetween(string $column, array $values)
 * @method static \Illuminate\Database\Eloquent\Builder groupBy(string|array $columns)
 * @method static \Illuminate\Database\Eloquent\Builder orderBy(string $column, string $direction = 'asc')
 * @method static int count()
 */

class Reservation extends Model
{
    protected $fillable = ['id','user_id','car_id','driver_id','dateStart','dateBack','driverAmount','type','status'];

    public function calculateDriverAmount(float $driverDailyRate): float
    {
        $days = 0;

        if ($this->dateStart && $this->dateBack) {
            $startDate = \Carbon\Carbon::parse($this->dateStart);
            $endDate   = \Carbon\Carbon::parse($this->dateBack);

            if ($endDate->greaterThanOrEqualTo($startDate)) {
                $days = $startDate->diffInDays($endDate);
                $days = round($days);
            }
        }

        // Calcul seulement si réservation avec chauffeur
        if ($this->type === 'leasing') {
            return $days * $driverDailyRate;
        }

        return 0;
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($reservation) {
            $driverDailyRate = Setting::get('driverDailyRate', 0); // tarif global fixé par l’entreprise

            if (empty($reservation->status)) {
                $reservation->status = 'en attente'; // valeur par défaut
            }

            if (empty($reservation->driver_id)) {
                $reservation->type = 'reservation';
            } else {
                $reservation->type = 'leasing';
            }
            $reservation->driverAmount = $reservation->calculateDriverAmount($driverDailyRate);
        });
    }

    public function User() {
        return $this->belongsTo(User::class);
    }

    public function car() {
        return $this->belongsTo(Car::class);
    }
    public function driver() {
        return $this->belongsTo(Driver::class);
    }
    public function invoice() {
        return $this->hasOne(Invoice::class);
    }

}
