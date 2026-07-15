<?php

namespace App\Models;

use App\Traits\RegistraMovimiento;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Documento extends Model
{
    use HasFactory, RegistraMovimiento, SoftDeletes;

    protected $fillable = [
        'id_expediente',
        'nombre_documento',
        'ruta_archivo',
        'nombre_original',
        'tipo_archivo',
        'tamano',
        'hash',
        'fecha_anexo',
    ];

    protected $casts = [
        'fecha_anexo' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    public function expediente()
    {
        return $this->belongsTo(Expediente::class, 'id_expediente');
    }

    public function getDerechoHabienteAttribute()
    {
        return $this->expediente?->derechoHabiente;
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESORES
    |--------------------------------------------------------------------------
    */

    // 🔥 Tipo de archivo dinámico (seguro)
    public function getTipoArchivoAttribute($value)
    {
        $ext = $value ?: pathinfo($this->ruta_archivo, PATHINFO_EXTENSION);
        return strtolower($ext);
    }

    // 🔥 URL pública (más confiable)
    public function getUrlAttribute()
    {
        return $this->ruta_archivo
            ? asset('storage/' . $this->ruta_archivo)
            : null;
    }

    // 🔥 Verifica si existe físicamente
    public function getExisteArchivoAttribute()
    {
        return $this->ruta_archivo
            ? Storage::disk('public')->exists($this->ruta_archivo)
            : false;
    }

    // 🔥 Tipo amigable
    public function getTipoAmigableAttribute()
    {
        return match ($this->tipo_archivo) {
            'pdf' => 'PDF',
            'doc', 'docx' => 'Word',
            'xls', 'xlsx', 'csv' => 'Excel',
            'ppt', 'pptx' => 'PowerPoint',
            'jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp' => 'Imagen',
            'mp4', 'avi', 'mov', 'mkv', 'wmv' => 'Video',
            'zip', 'rar' => 'Comprimido',
            'txt' => 'Texto',
            default => strtoupper($this->tipo_archivo ?? 'archivo'),
        };
    }

    // 🔥 Icono para UI
    public function getIconoAttribute()
    {
        return match ($this->tipo_archivo) {
            'pdf' => 'bi-file-earmark-pdf text-danger',
            'doc', 'docx' => 'bi-file-earmark-word text-primary',
            'xls', 'xlsx', 'csv' => 'bi-file-earmark-excel text-success',
            'ppt', 'pptx' => 'bi-file-earmark-slides text-warning',
            'jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp' => 'bi-image text-warning',
            'mp4', 'avi', 'mov', 'mkv', 'wmv' => 'bi-camera-video text-dark',
            'zip', 'rar' => 'bi-file-earmark-zip text-secondary',
            'txt' => 'bi-file-earmark-text text-info',
            default => 'bi-file-earmark text-secondary',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | ELIMINACIÓN SEGURA
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        static::deleting(function ($documento) {

            if (
                $documento->ruta_archivo &&
                Storage::disk('public')->exists($documento->ruta_archivo) &&
                $documento->isForceDeleting()
            ) {
                Storage::disk('public')->delete($documento->ruta_archivo);
            }
        });
    }
}