<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Documento;
use App\Models\Expediente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentoController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Listado
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $documentos = Documento::with('expediente.derechoHabiente')
            ->orderByDesc('fecha_anexo')
            ->paginate(20);

        return Inertia::render('Documentos/Index', compact('documentos'));
    }

    /*
    |--------------------------------------------------------------------------
    | Crear
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        $expedientes = Expediente::orderBy('codigo')->get();

        return Inertia::render('Documentos/Create', compact('expedientes'));
    }

    /*
    |--------------------------------------------------------------------------
    | Guardar
    |--------------------------------------------------------------------------
    */
   public function store(Request $request)
    {
        $validated = $request->validate([
            'id_expediente'    => 'required|exists:expedientes,id',
            'nombre_documento' => 'required|string|max:255',
            'ruta_archivo'     => 'required|file|max:51200',
            'fecha_anexo'      => 'required|date',
        ]);

        $archivo = $request->file('ruta_archivo');

        $carpeta = 'documentos/' . $validated['id_expediente'];

        if (!$archivo->isValid()) {
            return back()->with('error', 'Archivo inválido.');
        }

        $extensionesPermitidas = [
            'pdf','doc','docx','xls','xlsx','ppt','pptx',
            'jpg','jpeg','png','gif','bmp','webp',
            'mp4','avi','mov','wmv','mkv',
            'txt','csv','zip','rar'
        ];

        $extension = strtolower($archivo->getClientOriginalExtension());

        if (!in_array($extension, $extensionesPermitidas)) {
            return back()->with('error', 'Tipo de archivo no permitido.');
        }

        $carpeta = 'documentos/' . $validated['id_expediente'];

        $nombreArchivo = time() . '_' . uniqid() . '.' . $extension;

        $ruta = $archivo->storeAs($carpeta, $nombreArchivo, 'public');

        Documento::create([
            'id_expediente'    => $validated['id_expediente'],
            'nombre_documento' => $validated['nombre_documento'],
            'ruta_archivo'     => $ruta,
            'nombre_original'  => $archivo->getClientOriginalName(),
            'tipo_archivo'     => $extension,
            'tamano'           => $archivo->getSize(),
            'hash'             => md5_file($archivo->getRealPath()),
            'fecha_anexo'      => $validated['fecha_anexo'],
        ]);

        return redirect()
            ->route('documentos.index')
            ->with('success', 'Archivo subido correctamente.');
    } 

    
    /*
    |--------------------------------------------------------------------------
    | Mostrar
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $documento = Documento::with('expediente.derechoHabiente')
            ->findOrFail($id);

        return Inertia::render('Documentos/Show', compact('documento'));
    }

    /*
    |--------------------------------------------------------------------------
    | Editar
    |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $documento = Documento::findOrFail($id);

        $expedientes = Expediente::orderBy('codigo')->get();

        return Inertia::render('Documentos/Edit', compact('documento', 'expedientes'));
    }

    /*
    |--------------------------------------------------------------------------
    | Actualizar
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'id_expediente'    => 'required|exists:expedientes,id',
            'nombre_documento' => 'required|string|max:255',
            'ruta_archivo'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:51200',
            'fecha_anexo'      => 'required|date',
        ]);

        $documento = Documento::findOrFail($id);

        DB::transaction(function () use ($request, $validated, $documento) {

            $datos = $validated;

            /*
            |--------------------------------------------------------------------------
            | Si se sube un nuevo archivo
            |--------------------------------------------------------------------------
            */
            if ($request->hasFile('ruta_archivo')) {

                $archivo = $request->file('ruta_archivo');

                $carpeta = 'documentos/' . $validated['id_expediente'];

                $extension = strtolower($archivo->getClientOriginalExtension());

                $nombreArchivo =
                    Str::slug($validated['nombre_documento']) .
                    '_' .
                    time() .
                    '.' .
                    $extension;

                $ruta = $archivo->storeAs($carpeta, $nombreArchivo, 'public');

                /*
                | Eliminar archivo anterior
                */
                if ($documento->ruta_archivo &&
                    Storage::disk('public')->exists($documento->ruta_archivo)) {

                    Storage::disk('public')->delete($documento->ruta_archivo);
                }

                $datos['ruta_archivo']    = $ruta;
                $datos['nombre_original'] = $archivo->getClientOriginalName();
                $datos['tipo_archivo']    = $extension;
                $datos['tamano']          = $archivo->getSize();
                $datos['hash']            = md5_file($archivo->getRealPath());
            }

            $documento->update($datos);
        });

        return redirect()
            ->route('documentos.index')
            ->with('success', 'Documento actualizado correctamente');
    }

    /*
    |--------------------------------------------------------------------------
    | Eliminar (Soft Delete)
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $documento = Documento::findOrFail($id);

        $documento->eliminado_por = auth()->id();
        $documento->save();

        $documento->delete();

        return redirect()
            ->route('documentos.index')
            ->with('success', 'Documento eliminado correctamente');
    }
}