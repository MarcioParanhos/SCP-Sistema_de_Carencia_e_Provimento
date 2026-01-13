<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngressoCandidato extends Model
{
    use HasFactory;

    protected $table = 'ingresso_candidatos';
    protected $guarded = [];
}
