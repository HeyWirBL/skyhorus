<?php

namespace App\Http\Controllers;

use App\Models\CromatografiaLiquida;
use App\Models\Pozo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class CromatografiaLiquidaController extends Controller
{
    /**
     * Display a list of well chromatographies.
     */
    public function index(Request $request, CromatografiaLiquida $cromatografiaLiquida, Pozo $pozo): Response
    {
        $can = [
            'createCromatografiaLiquida' => Auth::user()->can('create', CromatografiaLiquida::class),
            'updateCromatografiaLiquida' => Auth::user()->can('update', CromatografiaLiquida::class),
            'restoreCromatografiaLiquida' => Auth::user()->can('restore', CromatografiaLiquida::class),
            'deleteCromatografiaLiquida' => Auth::user()->can('delete', CromatografiaLiquida::class),
        ];
        $filters = $request->only('search', 'trashed');
        $cromatografiaLiquidas = $cromatografiaLiquida->filter($filters)
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString()
            ->through(fn ($cl) => [
                'id' => $cl->id,
                'documento' => json_decode($cl->documento),
                'pozo_id' => $cl->pozo_id,
                'fecha_hora' => $cl->fecha_hora,
                'deleted_at' => $cl->deleted_at,
                'pozo' => optional($cl->pozo)->only('nombre_pozo', 'deleted_at'),
            ]);

        $pozos = $pozo->orderByDesc('id')->get()->map->only('id', 'nombre_pozo');

        return Inertia::render('CromatografiaLiquidas/Index', compact('can', 'filters', 'cromatografiaLiquidas', 'pozos'));
    }

    /**
     * Store a newly created document in database.
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, CromatografiaLiquida $cromatografiaLiquida): RedirectResponse
    {
        try {            
            $request->validate([
                'documento.*' => ['required', 'max:8000', 'mimes:pdf'], // MAX 8 MB per file
                'pozo_id' => ['required', Rule::exists('pozos', 'id')],
                'fecha_hora' => ['required', 'date'],
            ]);

            $hasFiles = $request->hasFile('documento');

            if ($hasFiles) {
                $files = $request->file('documento');

                foreach ($files as $file) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    // store the file in the "files" directory inside the "storage/app/public" disk
                    $path = Storage::disk('public')->putFileAs('files', $file, $filename);
                    $cromatografiaLiquida = $cromatografiaLiquida->create([
                        'pozo_id' => $request->input('pozo_id'),
                        'fecha_hora' => $request->input('fecha_hora'),
                        'documento' => json_encode([
                            'name' => 'storage/' . $path, // generate a public URL for the file
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
        } catch (\Illuminate\Validation\ValidationException $e) {
            return Redirect::back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return Redirect::back()->with('error', 'Error al subir el archivo: ' . $e->getMessage());
        }
    }

   
     /**
     * Update document's information in database.
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, CromatografiaLiquida $cromatografiaLiquida): RedirectResponse
    {
        try {            
            $request->validate([                
                'pozo_id' => ['required', Rule::exists('pozos', 'id')],
                'fecha_hora' => ['required', 'date'],
            ]);

            $hasFiles = $request->hasFile('documento');

            if ($hasFiles) {
                $request->validate([
                    'documento.*' => ['required', 'max:8000', 'mimes:pdf'], // MAX 8 MB per file
                ]);

                $files = $request->file('documento');

                foreach ($files as $file) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    // store the file in the "files" directory inside the "storage/app/public" disk
                    $path = Storage::disk('public')->putFileAs('files', $file, $filename);
                    $cromatografiaLiquida->update([                        
                        'documento' => json_encode([
                            'name' => 'storage/' . $path, // generate a public URL for the file
                            'usrName' => $filename,
                            'size' => $file->getSize(),
                            'type' => $file->getMimeType(),
                        ]),
                    ]);
                }                                
            }

            $cromatografiaLiquida->update([
                'pozo_id' => $request->input('pozo_id'),
                'fecha_hora' => $request->input('fecha_hora'),
            ]);
        
            if (!$hasFiles && !$cromatografiaLiquida->documento) {
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
            $documento = CromatografiaLiquida::withTrashed()->findOrFail($id);

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
        } catch (\Exception $e) {
            return back()->with('error', 'Error al descargar el archivo: ' . $e->getMessage());
        }
    }

    /**
     * Delete temporary an specific well cromatography.
     */
    public function destroy(CromatografiaLiquida $cromatografiaLiquida): RedirectResponse
    {
        $cromatografiaLiquida->delete();
        return Redirect::back()->with('success', 'Documento eliminado.');
    }

    /**
     * Delete multiple well cromatographies.
     */
    public function destroyAll(Request $request, CromatografiaLiquida $cromatografiaLiquida): RedirectResponse
    {
        $ids = explode(',', $request->query('ids', ''));
        $cromatografiaLiquida->whereIn('id', $ids)->delete();
        return Redirect::back()->with('success', 'Documentos eliminados.');
    }

    /**
     * Restore the well cromatography.
     */
    public function restore(CromatografiaLiquida $cromatografiaLiquida): RedirectResponse
    {
        $cromatografiaLiquida->restore();
        return Redirect::back()->with('success', 'Documento restablecido.');
    }

    /**
     * Restore multiple well cromatographies.
     */
    public function restoreAll(Request $request, CromatografiaLiquida $cromatografiaLiquida): RedirectResponse
    {        
        $ids = explode(',', $request->query('ids', ''));
        $cromatografiaLiquida->whereIn('id', $ids)->restore();       
        return Redirect::back()->with('success', 'Documentos restablecidos.');
    }
}
