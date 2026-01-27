<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngressoEncaminhamento extends Model
{
    use HasFactory;

    protected $table = 'ingresso_encaminhamentos';
    protected $guarded = [];

    public function servidor()
    {
        return $this->belongsTo(Servidore::class, 'servidor_id');
    }

    public function candidato()
    {
        return $this->belongsTo(IngressoCandidato::class, 'ingresso_candidato_id');
    }
}
