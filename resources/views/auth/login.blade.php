<x-guest-layout>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <!-- <div style="text-align:center;">
            <img src="/images/teste.svg" alt="Logo do Sistema" style="display:block; margin:auto; width:100%;">
        </div> -->
        <div style="text-align:center;">
            <img src="/images/SCP.png" alt="Logo do Sistema" style="display:block; margin:auto; width:90%;">
        </div>
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Senha')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Relembre-me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            <div style="text-align:center;">
                <img src="/images/teste.svg" alt="Logo do Sistema" style="display:block; margin:auto; width:180px;">
            </div>
            <x-primary-button class="ml-3">
                {{ __('Acessar') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>