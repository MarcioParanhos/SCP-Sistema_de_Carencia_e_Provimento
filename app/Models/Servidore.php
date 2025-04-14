<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servidore extends Model
{
    protected $guarded = [];
    use HasFactory;

    public function servidor()
    {
        return $this->hasMany(RegularizacaoFuncional::class, 'servidor_id');
    }

    public function vagasReservas()
{
    return $this->hasMany(VagaReserva::class);
}
}
