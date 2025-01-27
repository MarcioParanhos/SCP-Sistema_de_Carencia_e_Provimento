<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegularizacaoFuncional extends Model
{

    protected $table = 'regularizacoes_funcionais';
    protected $primaryKey = 'id';
    protected $guarded = [];
    use HasFactory;

    public function ueeOrigem()
    {
        return $this->belongsTo(Uee::class, 'uee_origem_id');
    }

    public function ueeDestino()
    {
        return $this->belongsTo(Uee::class, 'uee_destino_id');
    }

    public function servidor()
    {
        return $this->belongsTo(Servidore::class, 'servidor_id');
    }

}