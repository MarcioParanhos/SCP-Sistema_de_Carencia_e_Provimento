<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VagaReserva extends Model
{

    protected $table = 'vagas_reservas';
    use HasFactory;

    public function carencia()
    {
        return $this->belongsTo(Carencia::class);
    }

    public function servidor()
    {
        return $this->belongsTo(Servidore::class); // ou 'App\Models\Servidore' se necessário
    }
}
