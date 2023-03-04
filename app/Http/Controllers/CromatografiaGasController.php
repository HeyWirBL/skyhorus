<?php

namespace App\Http\Controllers;

use App\Models\CromatografiaGas;
use App\Models\Pozo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class CromatografiaGasController extends Controller
{
    /**
     * Display a list of pozo chromatographies.
     */
    public function index(Request $request, CromatografiaGas $cromatografiaGas, Pozo $pozo): Response
    {
        $can = [
            'createCromatografiaGas' => Auth::user()->can('create', CromatografiaGas::class),
            'updateCromatografiaGas' => Auth::user()->can('update', CromatografiaGas::class),
            'restoreCromatografiaGas' => Auth::user()->can('restore', CromatografiaGas::class),
            'deleteCromatografiaGas' => Auth::user()->can('delete', CromatografiaGas::class),
        ];
        $filters = $request->only('search', 'trashed');
        $cromatografiaGases = $cromatografiaGas->filter($filters)
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString()
            ->through(fn ($cg) => [
                'id' => $cg->id,
                'documento' => json_decode($cg->documento),
                'pozo_id' => $cg->pozo_id,
                'fecha_hora' => $cg->fecha_hora,
                'deleted_at' => $cg->deleted_at,
                'pozo' => optional($cg->pozo)->only('nombre_pozo', 'deleted_at'),
            ]);

        $pozos = $pozo->orderByDesc('id')->get()->map->only('id', 'nombre_pozo');

        return Inertia::render('CromatografiaGases/Index', compact('can', 'filters', 'cromatografiaGases', 'pozos'));
    }

    /**
     * Store a newly created document in database.
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, CromatografiaGas $cromatografiaGas): RedirectResponse
    {
        try {            
            $request->validate([
                'documento.*' => ['required', 'max:8048', 'mimes:pdf'], // MAX 8 MB per file
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
                    $cromatografiaGas = $cromatografiaGas->create([
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
    public function update(Request $request, CromatografiaGas $cromatografiaGas): RedirectResponse
    {
        try {            
            $request->validate([                
                'pozo_id' => ['required', Rule::exists('pozos', 'id')],
                'fecha_hora' => ['required', 'date'],
            ]);

            $hasFiles = $request->hasFile('documento');

            if ($hasFiles) {
                $request->validate([
                    'documento.*' => ['required', 'max:8048', 'mimes:pdf'], // MAX 8 MB per file
                ]);

                $files = $request->file('documento');

                foreach ($files as $file) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    // store the file in the "files" directory inside the "storage/app/public" disk
                    $path = Storage::disk('public')->putFileAs('files', $file, $filename);
                    $cromatografiaGas->update([                        
                        'documento' => json_encode([
                            'name' => 'storage/' . $path, // generate a public URL for the file
                            'usrName' => $filename,
                            'size' => $file->getSize(),
                            'type' => $file->getMimeType(),
                        ]),
                    ]);
                }                                
            }

            $cromatografiaGas->update([
                'pozo_id' => $request->input('pozo_id'),
                'fecha_hora' => $request->input('fecha_hora'),
            ]);
        
            if (!$hasFiles && !$cromatografiaGas->documento) {
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
            $documento = CromatografiaGas::withTrashed()->findOrFail($id);

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
    public function destroy(CromatografiaGas $cromatografiaGas): RedirectResponse
    {
        $cromatografiaGas->delete();
        return Redirect::back()->with('success', 'Documento eliminado.');
    }

    /**
     * Delete multiple well cromatographies.
     */
    public function destroyAll(Request $request, CromatografiaGas $cromatografiaGas): RedirectResponse
    {
        $ids = explode(',', $request->query('ids', ''));
        $cromatografiaGas->whereIn('id', $ids)->delete();
        return Redirect::back()->with('success', 'Documentos eliminados.');
    }

    /**
     * Restore the well cromatography.
     */
    public function restore(CromatografiaGas $cromatografiaGas): RedirectResponse
    {
        $cromatografiaGas->restore();
        return Redirect::back()->with('success', 'Documento restablecido.');
    }

    /**
     * Restore multiple well cromatographies.
     */
    public function restoreAll(Request $request, CromatografiaGas $cromatografiaGas): RedirectResponse
    {        
        $ids = explode(',', $request->query('ids', ''));
        $cromatografiaGas->whereIn('id', $ids)->restore();       
        return Redirect::back()->with('success', 'Documentos restablecidos.');
    }       
}
