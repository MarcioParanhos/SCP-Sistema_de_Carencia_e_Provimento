<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NumCop extends Model
{

    protected $table = 'num_cop';
    protected $fillable = [
        'num',
        'quantidade', // Esta linha é essencial para que o decrement() funcione.
        // Adicione outras colunas da sua tabela num_cop aqui, se houver.
    ];

    

    use HasFactory;
}
