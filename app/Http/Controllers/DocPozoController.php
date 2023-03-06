<?php

namespace App\Http\Controllers;

use App\Models\DocPozo;
use App\Models\Pozo;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Redirect;
use Illuminate\Support\Facades\Storage;

class DocPozoController extends Controller
{
    /**
     * Display a listing of pozo documents.
     */
    public function index(Request $request, DocPozo $docPozo, Pozo $pozo): Response
    {
        $can = [
            'createDocPozo' => Auth::user()->can('create', DocPozo::class),
            'editDocPozo' => Auth::user()->can('update', DocPozo::class),
            'restoreDocPozo' => Auth::user()->can('restore', DocPozo::class),
            'deleteDocPozo' => Auth::user()->can('delete', DocPozo::class),
        ];
        $filters = $request->only('search', 'trashed');        
        $docPozos = $docPozo->filter($filters)
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString()
            ->through(fn ($dp) => [
                'id' => $dp->id,
                'documento' => json_decode($dp->documento),
                'pozo_id' => $dp->pozo_id,
                'fecha_hora' => $dp->fecha_hora,
                'deleted_at' => $dp->deleted_at,
                'pozo' => optional($dp->pozo)->only('nombre_pozo', 'deleted_at'),
            ]);

        $pozos = $pozo->orderByDesc('id')->get()->map->only('id', 'nombre_pozo');

        return Inertia::render('DocPozos/Index', compact('can', 'filters', 'docPozos', 'pozos'));
    }

    /**
     * Store a newly created document in database.
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, DocPozo $docPozo): RedirectResponse
    {
        try {            
            $request->validate([
                'documento.*' => ['required', 'mimes:pdf'],
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
                    $docPozo = $docPozo->create([
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
    public function update(Request $request, DocPozo $docPozo): RedirectResponse
    {
        try {            
            $request->validate([                
                'pozo_id' => ['required', Rule::exists('pozos', 'id')],
                'fecha_hora' => ['required', 'date'],
            ]);

            $hasFiles = $request->hasFile('documento');

            if ($hasFiles) {
                $request->validate([
                    'documento.*' => ['required', 'mimes:pdf'],
                ]);

                $files = $request->file('documento');

                foreach ($files as $file) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    // store the file in the "files" directory inside the "storage/app/public" disk
                    $path = Storage::disk('public')->putFileAs('files', $file, $filename);
                    $docPozo->update([                        
                        'documento' => json_encode([
                            'name' => 'storage/' . $path, // generate a public URL for the file
                            'usrName' => $filename,
                            'size' => $file->getSize(),
                            'type' => $file->getMimeType(),
                        ]),
                    ]);
                }                                
            }

            $docPozo->update([
                'pozo_id' => $request->input('pozo_id'),
                'fecha_hora' => $request->input('fecha_hora'),
            ]);
        
            if (!$hasFiles && !$docPozo->documento) {
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
            $documento = DocPozo::withTrashed()->findOrFail($id);

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
     * Delete temporary an specific document.
     */
    public function destroy(DocPozo $docPozo): RedirectResponse
    {
        $docPozo->delete();
        return Redirect::back()->with('success', 'Documento de pozo eliminado.');
    }

    /**
     * Delete multiple documents.
     */
    public function destroyAll(Request $request, DocPozo $docPozo): RedirectResponse
    {
        $ids = explode(',', $request->query('ids', ''));
        $docPozo->whereIn('id', $ids)->delete();
        return Redirect::back()->with('success', 'Documentos de pozo eliminados.');
    }

    /**
     * Restore documents.
     */
    public function restore(DocPozo $docPozo): RedirectResponse
    {
        $docPozo->restore();
        return Redirect::back()->with('success', 'Documento de pozo restablecido.');
    }

    /**
     * Restore mutliple documents.
     */
    public function restoreAll(Request $request, DocPozo $docPozo): RedirectResponse
    {        
        $ids = explode(',', $request->query('ids', ''));
        $docPozo->whereIn('id', $ids)->restore();       
        return Redirect::back()->with('success', 'Documentos de pozo restablecidos.');
    }
}
