<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Panel del Docente
            </h2>
            <a href="{{ route('docente.tareas.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Crear Tarea
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-6 border-b pb-4">Mis Tareas Publicadas</h3>
                    
                    <div class="space-y-4">
                        @forelse ($tareas as $tarea)
                            <div class="border rounded-lg p-4 flex justify-between items-center hover:bg-gray-50 hover:shadow-md transition-all duration-200">
                                {{-- Información de la Tarea --}}
                                <div class="flex-grow">
                                    @if($tarea->materia)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800 mb-1">
                                            {{ $tarea->materia->codigo }} - {{ $tarea->materia->nombre }}
                                        </span>
                                    @endif
                                    <a href="{{ route('docente.tareas.show', $tarea) }}" class="block text-lg font-bold text-indigo-700 hover:underline">
                                        {{ $tarea->titulo }}
                                    </a>
                                    <div class="mt-2 flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-1.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span>Fecha Limite: {{ \Carbon\Carbon::parse($tarea->fecha_entrega)->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                                
                                {{-- Botones de Acción --}}
                                <div class="flex items-center space-x-3 flex-shrink-0 ml-4">
                                    <a href="{{ route('docente.tareas.edit', $tarea) }}" class="p-2 text-gray-500 rounded-full hover:bg-blue-100 hover:text-blue-700 transition-colors" title="Editar Tarea">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L16.732 3.732z"></path></svg>
                                    </a>
                                    <form method="POST" action="{{ route('docente.tareas.destroy', $tarea) }}" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta tarea? Se borrarán todas sus entregas permanentemente.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-500 rounded-full hover:bg-red-100 hover:text-red-700 transition-colors" title="Eliminar Tarea">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10 px-6 border-2 border-dashed rounded-lg bg-gray-50">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" /></svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay tareas creadas</h3>
                                <p class="mt-1 text-sm text-gray-500">Haz clic en "Crear Tarea" para empezar.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>