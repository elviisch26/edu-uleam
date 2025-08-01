<!-- Archivo: resources/views/auth/login.blade.php -->
<x-guest-layout>
    <!-- Session Status (esto es para mensajes como "contraseña reseteada") -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- INICIO DE LA SECCIÓN DE TEXTO MEJORADA -->
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800">
            Bienvenido a EduUleam
        </h2>

        <p class="text-sm text-gray-600 mt-1">
            Inicia sesión para acceder a la plataforma.
        </p>
    </div>
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Usamos un div con 'space-y-6' para dar espaciado automático a los elementos del formulario -->
        <div class="space-y-6">
            
            <!-- Campo de Correo Electrónico -->
            <div>
                <!-- Traducimos el label -->
                <x-input-label for="email" :value="__('Correo Electrónico')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Campo de Contraseña -->
            <div>
                <!-- Traducimos el label -->
                <x-input-label for="password" :value="__('Contraseña')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- SECCIÓN MEJORADA: Recordarme y Olvidé Contraseña -->
            <div class="flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <!-- Traducimos el texto y añadimos un pequeño margen -->
                    <span class="ms-2 text-sm text-gray-600">{{ __('Recordarme') }}</span>
                </label>

                <!-- Traducimos y estilizamos el enlace -->
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('¿Olvidaste tu contraseña?') }}
                    </a>
                @endif
            </div>

            <!-- BOTÓN DE INICIO DE SESIÓN MEJORADO -->
            <div class="flex items-center">
                <!-- Hacemos el botón de ancho completo y traducimos el texto -->
                <x-primary-button class="w-full justify-center">
                    {{ __('Iniciar Sesión') }}
                </x-primary-button>
            </div>

        </div>
    </form>
     <div class="text-center mt-6">
        <p class="text-sm text-gray-600">
            ¿No tienes una cuenta?
            <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-500 underline">
                Regístrate aquí
            </a>
        </p>
    </div>
</x-guest-layout>