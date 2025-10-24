<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// Importa os models relacionados
use App\Models\Profile;
use App\Models\Sector;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'sector_id',  // CORREÇÃO: Usar o nome da coluna da chave estrangeira
        'profile_id', // CORREÇÃO: Usar o nome da coluna da chave estrangeira
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Use 'hashed' para Laravel 10+
    ];

    /**
     * Define o relacionamento: Um usuário pertence a um Perfil (Profile).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profile() // Manteve o nome do relacionamento simples
    {
        // =======================================================================
        // CORREÇÃO: Explicitando a chave estrangeira correta ('profile_id').
        // =======================================================================
        // O segundo argumento informa ao Eloquent qual coluna na tabela 'users'
        // contém o ID que referencia a tabela 'profiles'.
        return $this->belongsTo(Profile::class, 'profile_id');
    }

    /**
     * Define o relacionamento: Um usuário pertence a um Setor (Sector).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sector()
    {
        // Aplicando a mesma lógica explícita para o relacionamento de setor.
        return $this->belongsTo(Sector::class, 'sector_id');
    }

    /**
     * Exemplo de relacionamento para Logs (assumindo que exista no seu código).
     */
    public function logs()
    {
        return $this->hasMany(Log::class);
    }
}

