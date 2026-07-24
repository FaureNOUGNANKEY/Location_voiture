<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Car
 *
 * @method static int count()
 * @method static \Illuminate\Database\Eloquent\Builder where(string $column, string $operator = null, mixed $value = null)
 * @method static \Illuminate\Database\Eloquent\Builder query()
 */

class Car extends Model
{
    protected $fillable = ['id','category_id','mark','type','model','color','photo','imatriculation','description','status','kmAmount','dayAmount','state','place','door','kilometrage','niveauCarburant','domage'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
