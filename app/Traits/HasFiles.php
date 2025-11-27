<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Trait para gestionar archivos en modelos.
 */
trait HasFiles
{
    /**
     * Almacena un archivo y devuelve información sobre él.
     *
     * @param UploadedFile $file El archivo a almacenar
     * @param string $directory El directorio donde almacenar
     * @param string $disk El disco de almacenamiento
     * @return array Información del archivo almacenado
     */
    public function storeFile(UploadedFile $file, string $directory, string $disk = 'public'): array
    {
        return [
            'path' => $file->store($directory, $disk),
            'name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime' => $file->getMimeType(),
            'extension' => $file->getClientOriginalExtension(),
        ];
    }

    /**
     * Elimina un archivo del almacenamiento.
     *
     * @param string $path La ruta del archivo
     * @param string $disk El disco de almacenamiento
     * @return bool True si se eliminó correctamente
     */
    public function deleteFile(string $path, string $disk = 'public'): bool
    {
        if ($path && Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->delete($path);
        }
        return false;
    }

    /**
     * Verifica si un archivo existe.
     *
     * @param string $path La ruta del archivo
     * @param string $disk El disco de almacenamiento
     * @return bool
     */
    public function fileExists(string $path, string $disk = 'public'): bool
    {
        return Storage::disk($disk)->exists($path);
    }

    /**
     * Obtiene la URL pública de un archivo.
     *
     * @param string $path La ruta del archivo
     * @param string $disk El disco de almacenamiento
     * @return string|null
     */
    public function getFileUrl(string $path, string $disk = 'public'): ?string
    {
        if ($this->fileExists($path, $disk)) {
            return Storage::disk($disk)->url($path);
        }
        return null;
    }

    /**
     * Obtiene el tamaño de un archivo en formato legible.
     *
     * @param string $path La ruta del archivo
     * @param string $disk El disco de almacenamiento
     * @return string
     */
    public function getFileSize(string $path, string $disk = 'public'): string
    {
        if (!$this->fileExists($path, $disk)) {
            return '0 B';
        }

        $bytes = Storage::disk($disk)->size($path);
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Descarga un archivo.
     *
     * @param string $path La ruta del archivo
     * @param string|null $name Nombre para la descarga
     * @param string $disk El disco de almacenamiento
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function downloadFile(string $path, ?string $name = null, string $disk = 'public')
    {
        if (!$this->fileExists($path, $disk)) {
            abort(404, 'Archivo no encontrado.');
        }

        return Storage::disk($disk)->download($path, $name);
    }
}
