<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Documento;
use App\Models\Expediente;
use App\Models\Usuario;
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
    public function index(Request $request)
    {
        $filtros = $request->only([
            'q', 'numero', 'tipo', 'fecha_desde', 'fecha_hasta', 'paciente', 'subido_por',
        ]);

        $documentos = Documento::with(['expediente.derechoHabiente', 'cargadoPor'])
            // Nombre del archivo (descriptivo u original).
            ->when($filtros['q'] ?? null, function ($query, $q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('nombre_documento', 'like', "%{$q}%")
                        ->orWhere('nombre_original', 'like', "%{$q}%");
                });
            })
            // Número de documento (folio interno = id).
            ->when($filtros['numero'] ?? null, fn ($query, $n) => $query->where('id', $n))
            // Tipo de documento (extensión).
            ->when($filtros['tipo'] ?? null, fn ($query, $t) => $query->where('tipo_archivo', $t))
            // Rango de fecha de anexo.
            ->when($filtros['fecha_desde'] ?? null, fn ($query, $f) => $query->whereDate('fecha_anexo', '>=', $f))
            ->when($filtros['fecha_hasta'] ?? null, fn ($query, $f) => $query->whereDate('fecha_anexo', '<=', $f))
            // Paciente (derechohabiente) por nombre o expediente.
            ->when($filtros['paciente'] ?? null, function ($query, $p) {
                $query->whereHas('expediente', function ($exp) use ($p) {
                    $exp->where('codigo', 'like', "%{$p}%")
                        ->orWhereHas('derechoHabiente', function ($dh) use ($p) {
                            $dh->where('nombre', 'like', "%{$p}%")
                               ->orWhere('apellido_paterno', 'like', "%{$p}%")
                               ->orWhere('apellido_materno', 'like', "%{$p}%");
                        });
                });
            })
            // Usuario que realizó la carga.
            ->when($filtros['subido_por'] ?? null, fn ($query, $u) => $query->where('subido_por', $u))
            ->orderByDesc('fecha_anexo')
            ->paginate(20)
            ->withQueryString();

        $tipos = Documento::query()
            ->select('tipo_archivo')
            ->whereNotNull('tipo_archivo')
            ->distinct()
            ->orderBy('tipo_archivo')
            ->pluck('tipo_archivo');

        $usuarios = Usuario::orderBy('nombre_usuario')->get(['id', 'nombre_usuario']);

        return Inertia::render('Documentos/Index', compact('documentos', 'filtros', 'tipos', 'usuarios'));
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
            'subido_por'       => auth()->id(),
        ]);

        return redirect()
            ->route('documentos.index')
            ->with('success', 'Archivo subido correctamente.');
    }

    /*
    |--------------------------------------------------------------------------
    | Carga múltiple
    |--------------------------------------------------------------------------
    */
    public function bulkCreate()
    {
        $expedientes = Expediente::orderBy('codigo')->get();

        return Inertia::render('Documentos/BulkCreate', compact('expedientes'));
    }

    public function storeBulk(Request $request)
    {
        $validated = $request->validate([
            'id_expediente'  => 'required|exists:expedientes,id',
            'fecha_anexo'    => 'required|date',
            'archivos'       => 'required|array|min:1',
            'archivos.*'     => 'file|max:51200',
        ]);

        $extensionesPermitidas = [
            'pdf','doc','docx','xls','xlsx','ppt','pptx',
            'jpg','jpeg','png','gif','bmp','webp',
            'mp4','avi','mov','wmv','mkv',
            'txt','csv','zip','rar',
        ];

        $carpeta   = 'documentos/' . $validated['id_expediente'];
        $guardados = 0;
        $omitidos  = [];

        DB::transaction(function () use ($request, $validated, $carpeta, $extensionesPermitidas, &$guardados, &$omitidos) {
            foreach ($request->file('archivos') as $archivo) {
                if (!$archivo->isValid()) {
                    $omitidos[] = $archivo->getClientOriginalName();
                    continue;
                }

                $extension = strtolower($archivo->getClientOriginalExtension());

                if (!in_array($extension, $extensionesPermitidas)) {
                    $omitidos[] = $archivo->getClientOriginalName();
                    continue;
                }

                $nombreArchivo = time() . '_' . uniqid() . '.' . $extension;
                $ruta          = $archivo->storeAs($carpeta, $nombreArchivo, 'public');

                Documento::create([
                    'id_expediente'    => $validated['id_expediente'],
                    'nombre_documento' => pathinfo($archivo->getClientOriginalName(), PATHINFO_FILENAME),
                    'ruta_archivo'     => $ruta,
                    'nombre_original'  => $archivo->getClientOriginalName(),
                    'tipo_archivo'     => $extension,
                    'tamano'           => $archivo->getSize(),
                    'hash'             => md5_file($archivo->getRealPath()),
                    'fecha_anexo'      => $validated['fecha_anexo'],
                    'subido_por'       => auth()->id(),
                ]);

                $guardados++;
            }
        });

        $mensaje = "{$guardados} documento(s) subido(s) correctamente.";
        if (!empty($omitidos)) {
            $mensaje .= ' Omitidos (tipo no permitido): ' . implode(', ', $omitidos) . '.';
        }

        return redirect()
            ->route('documentos.index')
            ->with('success', $mensaje);
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