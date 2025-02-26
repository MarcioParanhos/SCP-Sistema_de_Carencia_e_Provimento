<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProvimentosEncaminhado extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'provimentos_encaminhados';

    protected $fillable = ['disciplina', 'matutino', 'vespertino', 'noturno', 'nome', 'server_1_situation', 'pch', 'server_2_situation'];

    public function servidorEncaminhado()
    {
        return $this->belongsTo(ServidoresEncaminhado::class, 'servidor_encaminhado_id');
    }

    public function uee()
    {
        return $this->belongsTo(Uee::class, 'uee_id');
    }

    public function servidorSubstituido()
    {
        return $this->belongsTo(Servidore::class, 'servidor_substituido_id');
    }

    public function segundoServidorSubstituido()
    {
        return $this->belongsTo(Servidore::class, 'segundo_servidor_subistituido');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function userUpdate()
    {
        return $this->belongsTo(User::class, 'user_update_id');
    }
}
