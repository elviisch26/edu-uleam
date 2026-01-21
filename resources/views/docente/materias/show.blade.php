<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <a href="{{ route('docente.materias.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 flex items-center mb-1">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Mis Materias
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800 mr-3">
                        {{ $materia->codigo }}
                    </span>
                    {{ $materia->nombre }}
                </h2>
            </div>
            <a href="{{ route('docente.materias.tareas.create', $materia) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Nueva Tarea
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if($materia->descripcion)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Descripcion</h3>
                        <p class="text-gray-700">{{ $materia->descripcion }}</p>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-3">Tareas de esta Materia</h3>
                    
                    @if($materia->tareas->isEmpty())
                        <div class="text-center py-10 px-6 border-2 border-dashed rounded-lg bg-gray-50">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay tareas en esta materia</h3>
                            <p class="mt-1 text-sm text-gray-500">Crea la primera tarea para tus estudiantes.</p>
                            <a href="{{ route('docente.materias.tareas.create', $materia) }}" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Crear Tarea
                            </a>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($materia->tareas as $tarea)
                                <div class="border rounded-lg p-4 flex justify-between items-center hover:bg-gray-50 hover:shadow-md transition-all duration-200">
                                    <div class="flex-grow">
                                        <a href="{{ route('docente.tareas.show', $tarea) }}" class="text-lg font-bold text-indigo-700 hover:underline">
                                            {{ $tarea->titulo }}
                                        </a>
                                        <div class="mt-2 flex items-center space-x-4 text-sm text-gray-600">
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                {{ \Carbon\Carbon::parse($tarea->fecha_entrega)->format('d/m/Y H:i') }}
                                            </span>
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                {{ $tarea->entregas_count }} entregas
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center space-x-3 flex-shrink-0 ml-4">
                                        <a href="{{ route('docente.tareas.show', $tarea) }}" class="p-2 text-gray-500 rounded-full hover:bg-blue-100 hover:text-blue-700 transition-colors" title="Ver Entregas">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </a>
                                        <a href="{{ route('docente.tareas.edit', $tarea) }}" class="p-2 text-gray-500 rounded-full hover:bg-blue-100 hover:text-blue-700 transition-colors" title="Editar Tarea">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L16.732 3.732z"></path></svg>
                                        </a>
                                        <form method="POST" action="{{ route('docente.tareas.destroy', $tarea) }}" onsubmit="return confirm('¿Seguro que quieres eliminar esta tarea?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-500 rounded-full hover:bg-red-100 hover:text-red-700 transition-colors" title="Eliminar Tarea">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
