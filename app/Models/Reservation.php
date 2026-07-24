<?php

namespace App\Models;

use App\Http\Resources\InvoiceResource;
use Illuminate\Database\Eloquent\Model;

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
