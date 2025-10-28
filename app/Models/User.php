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
use Illuminate\Support\Facades\Hash; // Importa a facade Hash

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
        'sector_id',
        'profile_id',
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
        // REMOVIDO: O cast 'hashed' não está disponível em versões < Laravel 9
        // 'password' => 'hashed',
    ];

    // =======================================================================
    // MUTATOR PARA HASHING DE SENHA (PARA LARAVEL < 9)
    // =======================================================================
    /**
     * Define o mutator para o atributo 'password'.
     * Este método será chamado automaticamente sempre que você tentar definir
     * um valor para o atributo 'password' (ex: $user->password = 'nova_senha').
     *
     * @param  string  $value A senha em texto plano.
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        // Usa a facade Hash para gerar o hash seguro da senha antes de
        // armazená-la no array de atributos do model.
        $this->attributes['password'] = Hash::make($value);
    }
    // =======================================================================


    /**
     * Define o relacionamento: Um usuário pertence a um Perfil (Profile).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profile()
    {
        return $this->belongsTo(Profile::class, 'profile_id');
    }

    /**
     * Define o relacionamento: Um usuário pertence a um Setor (Sector).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sector()
    {
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
