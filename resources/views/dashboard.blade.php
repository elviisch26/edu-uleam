<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel del Estudiante') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(Auth::user()->rol?->nombre === 'estudiante')
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-2xl font-bold">¡Hola, {{ Auth::user()->name }}!</h3>
                        <p class="text-gray-600 mt-1">Aquí puedes ver tus tareas y administrar tus entregas.</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-xl font-bold mb-4 border-b pb-3">Tus Tareas</h3>
                        <div class="space-y-4">
                            
                            @forelse($tareas as $tarea)
                                <a href="{{ route('estudiante.tareas.show', $tarea) }}" class="block border rounded-lg p-4 hover:bg-gray-50 hover:shadow-sm transition-all duration-200">
                                    <div class="flex flex-col md:flex-row justify-between md:items-center">
                                        
                                        <div class="flex-grow">
                                            <h4 class="text-lg font-bold text-indigo-700">{{ $tarea->titulo }}</h4>
                                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($tarea->descripcion, 150) }}</p>
                                            <div class="mt-2">
                                                <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-red-700 bg-red-100">
                                                    Fecha Límite: {{ \Carbon\Carbon::parse($tarea->fecha_entrega)->format('d/m/Y H:i') }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4 md:mt-0 md:ms-6 flex-shrink-0">
                                            @php
                                                $entrega = $entregas->get($tarea->id);
                                            @endphp

                                            @if($entrega)
                                                @if($entrega->calificacion)
                                                    <div class="text-center p-2 rounded-lg bg-blue-100 border border-blue-200">
                                                        <p class="text-sm text-blue-800 font-semibold">Calificación:</p>
                                                        {{-- CORRECCIÓN CRÍTICA: Se eliminó rtrim() para mostrar la nota correctamente --}}
                                                        <p class="font-bold text-2xl text-blue-700">{{ $entrega->calificacion->calificacion }} / 10</p>
                                                    </div>
                                                @else
                                                    <span class="inline-flex items-center px-4 py-2 bg-green-200 text-green-800 text-sm font-medium rounded-md">
                                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                        Entregada
                                                    </span>
                                                @endif
                                            @else
                                                <span class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-bold rounded-md hover:bg-indigo-700">
                                                    Realizar Entrega
                                                </span>
                                            @endif
                                        </div>

                                    </div>
                                </a>
                            @empty
                                <div class="text-center py-10 px-6 border-2 border-dashed rounded-lg bg-gray-50">
                                    <svg class="mx-auto h-12 w-12 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">¡Todo al día!</h3>
                                    <p class="mt-1 text-sm text-gray-500">No tienes tareas pendientes en este momento. ¡Buen trabajo!</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <p>Bienvenido al sistema EduUleam.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>