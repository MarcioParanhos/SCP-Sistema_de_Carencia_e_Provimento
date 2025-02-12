<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServidoresEncaminhado extends Model
{
    use HasFactory;

    protected $table = 'servidores_encaminhados';

    public function provimentosEncaminhados()
    {
        return $this->hasMany(ProvimentosEncaminhado::class, 'servidor_encaminhado_id');
    }

}
