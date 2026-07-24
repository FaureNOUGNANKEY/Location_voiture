<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @mixin \Illuminate\Database\Query\Builder
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property float $panneAmount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Panne newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Panne newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Panne query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Panne whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Panne whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Panne whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Panne whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Panne wherePanneAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Panne whereUpdatedAt($value)
 */

class Panne extends Model
{
    protected $fillable = ['id','name','description','panneAmount'];
}
