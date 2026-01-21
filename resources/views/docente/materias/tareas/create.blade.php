<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ route('docente.materias.show', $materia) }}" class="text-sm text-indigo-600 hover:text-indigo-800 flex items-center mb-1">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                {{ $materia->codigo }} - {{ $materia->nombre }}
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Nueva Tarea
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    
                    <!-- Indicador de Materia -->
                    <div class="mb-6 p-4 bg-indigo-50 rounded-lg border border-indigo-200">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <div>
                                <p class="text-sm text-indigo-600 font-medium">Esta tarea sera asignada a:</p>
                                <p class="text-lg font-bold text-indigo-800">{{ $materia->codigo }} - {{ $materia->nombre }}</p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('docente.materias.tareas.store', $materia) }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="titulo" class="block text-sm font-medium text-gray-700">Titulo de la Tarea</label>
                            <input type="text" 
                                   name="titulo" 
                                   id="titulo" 
                                   value="{{ old('titulo') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('titulo') border-red-500 @enderror" 
                                   required>
                            @error('titulo')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripcion e Instrucciones</label>
                            <textarea name="descripcion" 
                                      id="descripcion" 
                                      rows="6" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('descripcion') border-red-500 @enderror"
                                      required>{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="fecha_entrega" class="block text-sm font-medium text-gray-700">Fecha y Hora Limite de Entrega</label>
                            <input type="datetime-local" 
                                   name="fecha_entrega" 
                                   id="fecha_entrega" 
                                   value="{{ old('fecha_entrega') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('fecha_entrega') border-red-500 @enderror"
                                   required>
                            @error('fecha_entrega')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="archivo_guia" class="block text-sm font-medium text-gray-700">Archivo Guia (opcional)</label>
                            <input type="file" 
                                   name="archivo_guia" 
                                   id="archivo_guia" 
                                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 @error('archivo_guia') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">PDF, DOC, DOCX, JPG, PNG o ZIP. Maximo 5MB.</p>
                            @error('archivo_guia')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('docente.materias.show', $materia) }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Cancelar
                            </a>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Crear Tarea
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
