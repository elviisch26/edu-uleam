<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ route('docente.materias.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 flex items-center mb-1">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Mis Materias
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Nueva Materia
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('docente.materias.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="codigo" class="block text-sm font-medium text-gray-700">Codigo de la Materia</label>
                            <input type="text" 
                                   name="codigo" 
                                   id="codigo" 
                                   value="{{ old('codigo') }}"
                                   placeholder="Ej: TEC-401"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 uppercase @error('codigo') border-red-500 @enderror" 
                                   required>
                            @error('codigo')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre de la Materia</label>
                            <input type="text" 
                                   name="nombre" 
                                   id="nombre" 
                                   value="{{ old('nombre') }}"
                                   placeholder="Ej: Tecnologias Emergentes"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('nombre') border-red-500 @enderror" 
                                   required>
                            @error('nombre')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripcion (opcional)</label>
                            <textarea name="descripcion" 
                                      id="descripcion" 
                                      rows="3" 
                                      placeholder="Breve descripcion de la materia..."
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('descripcion') border-red-500 @enderror">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('docente.materias.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Cancelar
                            </a>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Crear Materia
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
