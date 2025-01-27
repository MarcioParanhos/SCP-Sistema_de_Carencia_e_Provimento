<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carencia extends Model
{
    protected $guarded = [];
    use HasFactory;

    public function uee()
    {
        return $this->belongsTo(Uee::class, 'uee_id', 'id');
    }
}
