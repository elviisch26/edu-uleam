<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Tarea
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">

                    <form method="POST" action="{{ route('docente.tareas.update', $tarea->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH') {{-- O 'PUT' --}}

                        {{-- Título de la Tarea --}}
                        <div class="mb-4">
                            <label for="titulo" class="block text-sm font-medium text-gray-700">Título de la Tarea</label>
                            <input type="text" 
                                   name="titulo" 
                                   id="titulo" 
                                   value="{{ old('titulo', $tarea->titulo) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('titulo') border-red-500 @enderror" 
                                   required>
                            @error('titulo')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Descripción e Instrucciones --}}
                        <div class="mb-4">
                            <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción e Instrucciones</label>
                            <textarea name="descripcion" 
                                      id="descripcion" 
                                      rows="6" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('descripcion') border-red-500 @enderror"
                                      required>{{ old('descripcion', $tarea->descripcion) }}</textarea>
                            @error('descripcion')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Fecha y Hora Límite --}}
                        <div class="mb-6">
                            <label for="fecha_entrega" class="block text-sm font-medium text-gray-700">Fecha y Hora Límite de Entrega</label>
                            <input type="datetime-local" 
                                   name="fecha_entrega" 
                                   id="fecha_entrega" 
                                   value="{{ old('fecha_entrega', $tarea->fecha_entrega) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('fecha_entrega') border-red-500 @enderror"
                                   required>
                            @error('fecha_entrega')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Botones de Acción --}}
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('docente.tareas.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Cancelar
                            </a>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Actualizar Tarea
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>