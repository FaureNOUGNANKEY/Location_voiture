<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Historic extends Model
{
    protected $fillable = ['id','user_id','activite','dateConnexion','heureDeconnexion'];

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
