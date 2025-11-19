<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use App\Models\Provimento;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // VALIDAÇÕES GERAIS
        Gate::define('view-cpg-technician', function (User $user) {

            // Garante que ambos os relacionamentos existam.
            if (!$user->profile || !$user->sector) {
                return false;
            }

            $profileId = $user->profile_id;
            $sectorId = $user->sector->id;

            // Verifica as combinações permitidas:
            return ($profileId === 1 && $sectorId === 1);   // Técnico (1) + Programação (1)
        });
        Gate::define('view-cpm-technician', function (User $user) {

            // Garante que ambos os relacionamentos existam.
            if (!$user->profile || !$user->sector) {
                return false;
            }

            $profileId = $user->profile_id;
            $sectorId = $user->sector->id;

            // Verifica as combinações permitidas:
            return ($profileId === 1 && $sectorId === 2);   // Técnico (1) + Provimento (2)
        });
        Gate::define('view-cpg-technician-or-administrator', function (User $user) {

            // Garante que ambos os relacionamentos existam.
            if (!$user->profile || !$user->sector) {
                return false;
            }

            $profileId = $user->profile_id;
            $sectorId = $user->sector->id;

            // Verifica as combinações permitidas:
            return ($profileId === 4 && $sectorId === 3) || // Administrador (4) + Administração (3)
                ($profileId === 1 && $sectorId === 1);   // Técnico (1) + Programação (1)
        });
        Gate::define('view-sensitive-validations-fields', function (User $user) {

            // Garante que ambos os relacionamentos existam.
            if (!$user->profile || !$user->sector) {
                return false;
            }

            $profileId = $user->profile_id;
            $sectorId = $user->sector->id;

            // Verifica as combinações permitidas:
            return ($profileId === 1 && $sectorId === 2) || // Tecnico (1) + Provimento (2)
                ($profileId === 4 && $sectorId === 3) || // Administrador (4) + Administração (3)
                ($profileId === 1 && $sectorId === 1);   // Tecnico (1) + Programação (1)
        });
        Gate::define('view-cpm-technician-or-administrator', function (User $user) {

            // Garante que ambos os relacionamentos existam.
            if (!$user->profile || !$user->sector) {
                return false;
            }

            $profileId = $user->profile_id;
            $sectorId = $user->sector->id;

            // Verifica as combinações permitidas:
            return ($profileId === 4 && $sectorId === 3) || // Administrador (4) + Administração (3)
                ($profileId === 1 && $sectorId === 2);   // Tecnico (1) + Provimento (2)
        });

        // PROVIMENTO
        Gate::define('view-blocked-provimento-details', function (User $user, Provimento $provimento) {

            // 1. Verifica a primeira condição: O provimento está bloqueado?
            if ($provimento->situacao === 'BLOQUEADO') {
                return true; // Se estiver bloqueado, qualquer usuário pode ver (conforme sua lógica original)
            }

            // 2. Garante que os relacionamentos do usuário existam antes de verificar IDs.
            if (!$user->profile || !$user->sector) {
                return false; // Usuário sem perfil ou setor não tem a permissão específica.
            }

            // 3. Verifica a segunda condição: O usuário é Tecnico (1) no setor de Provimento (2)?
            $isTechnicalInProvimento = ($user->profile_id === 1 && $user->sector->id === 2);

            // Retorna true se a segunda condição for atendida.
            return $isTechnicalInProvimento;
        });
        Gate::define('view-blocked-provimento', function (User $user) {

            // Garante que ambos os relacionamentos existam.
            if (!$user->profile || !$user->sector) {
                return false;
            }

            $profileId = $user->profile_id;
            $sectorId = $user->sector->id;

            // Verifica as combinações permitidas:
            return ($profileId === 4 && $sectorId === 3) || // Administrador (4) + Administração (3)
                ($profileId === 1 && $sectorId === 2);   // coordenador (2) + Provimento (2)
        });
        Gate::define('view-sensitive-validation-fields-cpm-coordinator', function (User $user) {

            // Garante que ambos os relacionamentos existam.
            if (!$user->profile || !$user->sector) {
                return false;
            }

            $profileId = $user->profile_id;
            $sectorId = $user->sector->id;

            // Verifica as combinações permitidas:
            return ($profileId === 1 && $sectorId === 2) || // Tecnico (1) + Provimento (2)
                ($profileId === 4 && $sectorId === 3) || // Administrador (4) + Administração (3)
                ($profileId === 2 && $sectorId === 2);   // coordenador (2) + Provimento (2)
        });
    }
}
