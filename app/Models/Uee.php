<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Uee extends Model
{
    protected $guarded = [];
    use HasFactory;

    protected $table = 'uees';

    public function regularizacoesFuncionais()
    {
        return $this->hasMany(RegularizacaoFuncional::class, function ($query) {
            $query->where('uee_origem_id', $this->id)
                ->orWhere('uee_destino_id', $this->id);
        });
    }
}
