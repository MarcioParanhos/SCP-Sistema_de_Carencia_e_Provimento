<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carencia extends Model
{
    protected $guarded = [];
    use HasFactory;

    public function uee()
    {
        return $this->belongsTo(Uee::class, 'uee_id', 'id');
    }

    public function vagasReservas()
    {
        return $this->hasMany(VagaReserva::class);
    }

    public function vagaReserva()
{
    return $this->hasOne(VagaReserva::class, 'carencia_id'); // ou a foreign key correta
}

}
