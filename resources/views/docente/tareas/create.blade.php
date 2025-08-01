<!-- Archivo: resources/views/docente/tareas/create.blade.php (CORREGIDO) -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Nueva Tarea') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">
                    <form method="POST" action="{{ route('docente.tareas.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="space-y-6">
                            <!-- Título de la Tarea -->
                            <div>
                                <x-input-label for="titulo" :value="__('Título de la Tarea')" />
                                {{-- En 'create', solo necesitamos old() para el valor --}}
                                <x-text-input id="titulo" class="block mt-1 w-full" type="text" name="titulo" :value="old('titulo')" required autofocus />
                                <x-input-error :messages="$errors->get('titulo')" class="mt-2" />
                            </div>

                            <!-- Descripción -->
                            <div>
                                <x-input-label for="descripcion" :value="__('Descripción e Instrucciones')" />
                                {{-- En 'create', solo necesitamos old() para el valor --}}
                                <textarea id="descripcion" name="descripcion" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('descripcion') }}</textarea>
                                <x-input-error :messages="$errors->get('descripcion')" class="mt-2" />
                            </div>

                            <!-- Archivo de Guía (Opcional) -->
                            <div>
                                <x-input-label for="archivo_guia" :value="__('Archivo de Guía (Opcional)')" />
                                <input id="archivo_guia" name="archivo_guia" type="file" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none mt-1">
                                <x-input-error :messages="$errors->get('archivo_guia')" class="mt-2" />
                            </div>

                            <!-- Fecha de Entrega -->
                            <div>
                                <x-input-label for="fecha_entrega" :value="__('Fecha y Hora Límite de Entrega')" />
                                {{-- En 'create', solo necesitamos old() para el valor --}}
                                <input id="fecha_entrega" name="fecha_entrega" type="datetime-local" value="{{ old('fecha_entrega') }}" required max="{{ now()->addYears(10)->format('Y-m-d\TH:i') }}" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <x-input-error :messages="$errors->get('fecha_entrega')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="mt-8 flex justify-end space-x-4">
                            <a href="{{ route('docente.tareas.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                Cancelar
                            </a>
                            <x-primary-button>
                                {{ __('Guardar Tarea') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>