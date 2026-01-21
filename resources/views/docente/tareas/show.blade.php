<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Entregas para la Tarea: {{ $tarea->titulo }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <a href="{{ route('docente.materias.index') }}" class="text-sm text-indigo-600 hover:underline mb-6 inline-block">← Volver a Mis Materias</a>

                <h3 class="text-xl font-bold">Instrucciones Originales</h3>
                <p class="mt-2 text-gray-700 bg-gray-50 p-4 rounded-md">{{ $tarea->descripcion }}</p>
                
                <hr class="my-6">

                <h3 class="text-xl font-bold">Entregas Recibidas</h3>

                @if (session('success'))
                    <div class="my-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="my-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                
                <div class="mt-4 border border-gray-200 rounded-lg">
                    <ul class="divide-y divide-gray-200">
                        @forelse($tarea->entregas as $entrega)
                            <li class="p-4 flex justify-between items-center hover:bg-gray-50">
                                <div class="w-1/4">
                                    <p class="font-semibold text-gray-900">{{ $entrega->user->name }}</p>
                                    <p class="text-sm text-gray-600">Entregado: {{ $entrega->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="w-3/4 flex items-center justify-end space-x-4">
                                    <a href="{{ route('docente.entregas.descargar', $entrega) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold whitespace-nowrap">
                                        Descargar Archivo
                                    </a>

                                    @if ($entrega->calificacion)
                                        <div class="text-right flex items-center space-x-2">
                                            <span class="p-2 bg-green-100 rounded-md font-bold text-green-700">Calificado: {{ $entrega->calificacion->calificacion }} / 10</span>
                                            <a href="{{ route('docente.calificaciones.edit', $entrega->calificacion->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-3 rounded text-sm">
                                                Editar
                                            </a>
                                        </div>
                                    @else
                                        <form method="POST" action="{{ route('docente.calificaciones.store') }}" class="flex-grow">
                                            @csrf
                                            <input type="hidden" name="entrega_id" value="{{ $entrega->id }}">

                                            @error('calificacion', 'retroalimentacion')
                                            <div class="mb-2 text-red-500 text-sm">Error al procesar la calificación. Inténtelo de nuevo.</div>
                                            @enderror

                                            <div class="flex items-start space-x-2">
                                                <input type="number" name="calificacion" step="0.01" min="0" max="10" placeholder="Nota" class="w-20 border-gray-300 rounded-md shadow-sm text-sm" required>
                                                <input type="text" name="retroalimentacion" placeholder="Comentario (opcional)" class="flex-grow border-gray-300 rounded-md shadow-sm text-sm">
                                                <button type="submit" class="px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 whitespace-nowrap">Calificar</button>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </li>
                        @empty
                            <li class="p-4 text-center text-gray-500">
                                Aún no se han recibido entregas para esta tarea.
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>