<!-- Archivo: resources/views/auth/reset-password.blade.php -->
<x-guest-layout>
    <!-- Encabezado -->
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800">
            Restablecer Contraseña
        </h2>
        <p class="text-sm text-gray-600 mt-1">
            Crea una nueva contraseña segura para tu cuenta.
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="space-y-6">
            <!-- Correo Electrónico -->
            <div>
                <x-input-label for="email" :value="__('Correo Electrónico')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Contraseña -->
            <div>
                <x-input-label for="password" :value="__('Nueva Contraseña')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirmar Contraseña -->
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirmar Nueva Contraseña')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <!-- Botón -->
            <div>
                <x-primary-button class="w-full justify-center">
                    {{ __('Restablecer Contraseña') }}
                </x-primary-button>
            </div>
        </div>
    </form>
</x-guest-layout>```

---

### **4. Archivo: `confirm-password.blade.php` (Confirmar Contraseña)**

```html
<!-- Archivo: resources/views/auth/confirm-password.blade.php -->
<x-guest-layout>
    <!-- Encabezado y descripción -->
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800">
            Confirmar Contraseña
        </h2>
        <p class="text-sm text-gray-600 mt-1">
            Esta es un área segura de la aplicación. Por favor, confirma tu contraseña para continuar.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="space-y-6">
            <!-- Contraseña -->
            <div>
                <x-input-label for="password" :value="__('Contraseña')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Botón -->
            <div>
                <x-primary-button class="w-full justify-center">
                    {{ __('Confirmar') }}
                </x-primary-button>
            </div>
        </div>
    </form>
</x-guest-layout>