<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngressoDocumento extends Model
{
    use HasFactory;

    protected $table = 'ingresso_documentos';
    protected $guarded = [];
}
