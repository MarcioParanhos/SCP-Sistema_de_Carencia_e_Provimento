<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provimento extends Model
{
    protected $guarded = [];
    use HasFactory;

    public function servidore()
    {
        return $this->belongsTo(Servidore::class, 'servidor_id');
    }
}
