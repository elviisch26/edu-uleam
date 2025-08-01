<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Calificación para: {{ $calificacion->entrega->user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">

                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Tarea: {{ $calificacion->entrega->tarea->titulo }}
                    </h3>
                    
                    <form method="POST" action="{{ route('docente.calificaciones.update', $calificacion->id) }}">
                        @csrf
                        @method('PUT')

                        {{-- Campo para la Calificación --}}
                        <div class="mb-4">
                            <label for="calificacion" class="block text-sm font-medium text-gray-700">Calificación (sobre 10)</label>
                            <input type="number" 
                                   name="calificacion" 
                                   id="calificacion" 
                                   step="0.01"
                                   min="0"
                                   max="10"
                                   value="{{ old('calificacion', $calificacion->calificacion) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('calificacion') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror" 
                                   required>
                            @error('calificacion')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Campo para la Retroalimentación --}}
                        <div class="mb-6">
                            <label for="retroalimentacion" class="block text-sm font-medium text-gray-700">Retroalimentación</label>
                            <textarea name="retroalimentacion" 
                                      id="retroalimentacion" 
                                      rows="5" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('retroalimentacion') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">{{ old('retroalimentacion', $calificacion->retroalimentacion) }}</textarea>
                            @error('retroalimentacion')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Botones de Acción --}}
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('docente.tareas.show', $calificacion->entrega->tarea_id) }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Cancelar
                            </a>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Actualizar Calificación
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>