<?php

namespace App\Http\Controllers;

use App\Models\Ano;
use App\Models\Directorio;
use App\Models\Documento;
use App\Models\MesDetalle;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class DocumentoController extends Controller
{
    /**
     * Display a listing of documents.
     */
    public function index(Request $request, Documento $documento, Directorio $directorio, Ano $ano, MesDetalle $mes): Response
    {
        $filters = $request->only('search', 'trashed');

        // Filter for year and month
        if ($request->has('year') || $request->has('month')) {
            $year = $request->input('year');
            $month = $request->input('month');

            $filters['year'] = $year;
            $filters['month'] = $month;
        }

        $documentos = $documento->with('directorio', 'ano', 'mesDetalle')
            ->filter($filters)
            ->latest()
            ->paginate(10)
            ->withQueryString()
            ->through(fn ($doc) => [
                'id' => $doc->id,
                'documento' => json_decode($doc->documento),
                'directorio_id' => $doc->directorio_id,
                'ano_id' => $doc->ano_id,
                'mes_detalle_id' => $doc->mes_detalle_id,
                'deleted_at' => $doc->deleted_at,
                'directorio' => optional($doc->directorio)->only('nombre_dir', 'deleted_at'),
                'ano' => optional($doc->ano)->only('ano', 'deleted_at'),
                'mes' => optional($doc->mesDetalle)->only('nombre'),
            ]);

        $directorios = $directorio->orderByDesc('id')->get()->map->only('id', 'nombre_dir');
        $anos = $ano->latest()->get()->map->only('id', 'ano');
        $meses = $mes->orderBy('id')->get()->map->only('id', 'nombre');

        return Inertia::render('Documentos/Index', compact('filters', 'documentos', 'directorios', 'anos', 'meses'));
    }

    /**
     * Store a newly created document in database.
     */
    public function store(Request $request, Documento $documento): RedirectResponse
    {
        try {            
            $request->validate([
                'documento.*' => ['required', 'max:10000'], // MAX 10MB per file
                'directorio_id' => ['required', Rule::exists('directorios', 'id')],
                'ano_id' => ['required', Rule::exists('anos', 'id')],
                'mes_detalle_id' => ['required', Rule::exists('mes_detalles', 'id')],
            ]);

            $hasFiles = $request->hasFile('documento');

            if ($hasFiles) {
                $files = $request->file('documento');

                foreach ($files as $file) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    // Store the file in the "files" directory inside the "storage/app/public" disk
                    $path = Storage::disk('public')->putFileAs('files', $file, $filename);

                    // Create a new record in the "documentos" table with the file details
                    $documento = $documento->create([
                        'directorio_id' => $request->input('directorio_id'),
                        'ano_id' => $request->input('ano_id'),
                        'mes_detalle_id' => $request->input('mes_detalle_id'),
                        'documento' => json_encode([
                            'name' => Storage::url($path), // generate a public URL for the file
                            'usrName' => $filename,
                            'size' => $file->getSize(),
                            'type' => $file->getMimeType(),
                        ]),
                    ]);
                }         
                return Redirect::back()->with('success', 'Subido correctamente.');
            } else {
                return Redirect::back()->with('error', 'No se han subido archivos en el formulario.');
            }
        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                return Redirect::back()->withErrors($e->errors())->withInput();
            } else {
                return Redirect::back()->with('error', 'Error al subir el archivo: ' . $e->getMessage());
            }
        }
    }

    
    /**
     * Update document's information in database.
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Documento $documento): RedirectResponse
    {
        try {            
            $request->validate([                
                'directorio_id' => ['required', Rule::exists('directorios', 'id')],
                'ano_id' => ['required', Rule::exists('anos', 'id')],
                'mes_detalle_id' => ['required', Rule::exists('mes_detalles', 'id')],
            ]);

            $hasFiles = $request->hasFile('documento');

            if ($hasFiles) {
                $request->validate([
                    'documento.*' => ['required', 'max:10000'],
                ]);

                $files = $request->file('documento');

                foreach ($files as $file) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    // store the file in the "files" directory inside the "storage/app/public" disk
                    $path = Storage::disk('public')->putFileAs('files', $file, $filename);
                    $documento->update([                        
                        'documento' => json_encode([
                            'name' => Storage::url($path), // generate a public URL for the file
                            'usrName' => $filename,
                            'size' => $file->getSize(),
                            'type' => $file->getMimeType(),
                        ]),
                    ]);
                }                                
            }

            $documento->update([
                'directorio_id' => $request->input('directorio_id'),
                'ano_id' => $request->input('ano_id'),
                'mes_detalle_id' => $request->input('mes_detalle_id'),
            ]);
        
            if (!$hasFiles && !$documento->documento) {
                return Redirect::back()->with('error', 'Debe subir al menos un archivo.');
            }
            
            return Redirect::back()->with('success', 'Actualizado correctamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return Redirect::back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return Redirect::back()->with('error', 'Error al subir el archivo: ' . $e->getMessage());
        }
    }

    /**
     * Download an specific documento store in disk.
     */
    public function download($id)
    {
        try {
            $documento = Documento::withTrashed()->findOrFail($id);

            if ($documento->trashed()) {
                return back()->with('error', 'Error al descargar el archivo: el archivo ha sido eliminado.');
            }
            
            $documentoData = json_decode($documento->documento, true);

            // Extract the file path and name from the documentoData array
            $filePath = $documentoData['name'];
            $fileName = $documentoData['usrName'];         

            // Get the full path of the file in the storage/app/public folder
            $fullPath = public_path($filePath);

            // Check if the file exists
            if (!file_exists($fullPath)) {
                return back()->with('error', 'Error al descargar el archivo: el archivo no existe.');
            }   

            // Set the response headers
            $headers = [
                'Content-Type' => $documentoData['type'],
                'Content-Disposition' => 'attachment; filename="'.$fileName.'"',
            ];

            return response()->download($fullPath, $fileName, $headers);
                       
            return back()->with('error', 'Error al descargar el archivo: el archivo no existe.');       
        } catch (\Exception $e) {
            return back()->with('error', 'Error al descargar el archivo: ' . $e->getMessage());
        }
    }

    /**
     * Delete temporary an specific document.
     */
    public function destroy(Documento $documento): RedirectResponse
    {
        $documento->delete();
        return Redirect::back()->with('success', 'Documento eliminado.');
    }

    /**
     * Delete multiple documents.
     */
    public function destroyAll(Request $request, Documento $documento): RedirectResponse
    {
        $ids = explode(',', $request->query('ids', ''));
        $documento->whereIn('id', $ids)->delete();
        return Redirect::back()->with('success', 'Documentos eliminados.');
    }

    /**
     * Restore documents.
     */
    public function restore(Documento $documento): RedirectResponse
    {
        $documento->restore();
        return Redirect::back()->with('success', 'Documento restablecido.');
    }

    /**
     * Restore mutliple documents.
     */
    public function restoreAll(Request $request, Documento $documento): RedirectResponse
    {        
        $ids = explode(',', $request->query('ids', ''));
        $documento->whereIn('id', $ids)->restore();       
        return Redirect::back()->with('success', 'Documentos restablecidos.');
    }
}
