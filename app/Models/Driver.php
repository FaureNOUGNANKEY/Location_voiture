<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Driver
 *
 * @method static int count()
 * @method static \Illuminate\Database\Eloquent\Builder where(string $column, string $operator = null, mixed $value = null)
 * @method static \Illuminate\Database\Eloquent\Builder query()
 */
class Driver extends Model
{
    protected $fillable = ['id','lastname','firstname','photo','phone','status','active'];

    public function reservations() {
        return $this->hasMany(Reservation::class);
    }
}
