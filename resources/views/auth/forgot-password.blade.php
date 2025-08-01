<!-- Archivo: resources/views/auth/forgot-password.blade.php -->
<x-guest-layout>
    <!-- Encabezado y descripción -->
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800">
            ¿Olvidaste tu contraseña?
        </h2>
        <p class="text-sm text-gray-600 mt-1">
            No hay problema. Ingresa tu correo y te enviaremos un enlace para restablecerla.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="space-y-6">
            <!-- Correo Electrónico -->
            <div>
                <x-input-label for="email" :value="__('Correo Electrónico')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Botón de Envío -->
            <div>
                <x-primary-button class="w-full justify-center">
                    {{ __('Enviar Enlace de Restablecimiento') }}
                </x-primary-button>
            </div>
        </div>
    </form>
</x-guest-layout>