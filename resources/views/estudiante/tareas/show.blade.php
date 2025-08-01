<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Entregar Tarea: {{ $tarea->titulo }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">

                <a href="{{ route('dashboard') }}" class="text-sm text-indigo-600 hover:underline mb-6 inline-block">← Volver al Panel</a>

                <h3 class="text-2xl font-bold text-gray-900">Detalles de la Tarea</h3>
                <p class="mt-2 text-gray-700">{{ $tarea->descripcion }}</p>
                <div class="mt-4">
                    <span class="text-sm font-semibold inline-block py-1 px-3 uppercase rounded-full text-red-600 bg-red-200">
                        Fecha Límite: {{ \Carbon\Carbon::parse($tarea->fecha_entrega)->format('d/m/Y H:i') }}
                    </span>
                </div>

                @if ($tarea->ruta_archivo_guia)
                    <div class="mt-6">
                        <h4 class="text-lg font-bold text-gray-800">Material de Apoyo</h4>
                        <div class="mt-2 border border-gray-200 rounded-lg p-3 hover:bg-gray-50">
                            <a href="{{ Storage::url($tarea->ruta_archivo_guia) }}" target="_blank" download="{{ $tarea->nombre_archivo_guia ?? basename($tarea->ruta_archivo_guia) }}" class="flex items-center text-indigo-600 hover:text-indigo-800 font-semibold">
                                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                {{ $tarea->nombre_archivo_guia ?? basename($tarea->ruta_archivo_guia) }}
                            </a>
                        </div>
                    </div>
                @endif

                <hr class="my-8">

                @if ($entregaRealizada)
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-2xl font-bold text-gray-900">Tu Entrega</h3>
                        <p class="mt-2 text-sm text-gray-600">Entregaste este trabajo el {{ $entregaRealizada->created_at->format('d/m/Y \a \l\a\s H:i') }}.</p>
                        @if ($entregaRealizada->calificacion)
                            <div class="mt-4 bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4">
                                <p class="font-bold">Calificación Obtenida:</p>
                                <p class="text-3xl font-bold mt-1">{{ rtrim(rtrim($entregaRealizada->calificacion->calificacion, ), '.') }} / 10</p>
                                @if ($entregaRealizada->calificacion->retroalimentacion)
                                    <p class="font-bold mt-4">Comentarios del Profesor:</p>
                                    <blockquote class="mt-1 italic border-l-2 border-blue-300 pl-3">"{{ $entregaRealizada->calificacion->retroalimentacion }}"</blockquote>
                                @endif
                            </div>
                        @else
                            <div class="mt-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
                                <p class="font-bold">Pendiente de Calificación</p>
                                <p>Tu profesor ha recibido tu entrega y la calificará pronto.</p>
                            </div>
                        @endif
                    </div>
                @else
                    <h3 class="text-2xl font-bold text-gray-900">Subir mi Trabajo</h3>
                    <form method="POST" action="{{ route('estudiante.entregas.store', $tarea) }}" enctype="multipart/form-data" class="mt-4">
                        @csrf
                        <div class="mb-4">
                            <label for="archivo_entrega" class="block text-gray-700 text-sm font-bold mb-2">Selecciona tu archivo (PDF, Word, ZIP - Máx 2MB):</label>
                            <input type="file" name="archivo_entrega" id="archivo_entrega" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" required>
                            @error('archivo_entrega')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="w-full bg-green-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-green-700 transition duration-300 ease-in-out">
                            Confirmar Entrega
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>