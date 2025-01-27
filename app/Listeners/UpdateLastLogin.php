<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Carbon\Carbon;
use App\Models\User; // Importe o modelo User aqui
use Illuminate\Support\Facades\Auth;

class UpdateLastLogin
{
    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $user = $event->user;

        // Certifique-se de que a instância do usuário é do tipo User
        if ($user instanceof User) {
            $user->last_login = Carbon::now();
            $user->save();
        }
    }
}
