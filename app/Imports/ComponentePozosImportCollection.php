<?php

namespace App\Imports;

use App\Models\ComponentePozo;
use App\Models\Pozo;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ComponentePozosImportCollection implements ToCollection, WithHeadingRow
{
    private $pozos;

    public function __construct()
    {
        $this->pozos = Pozo::pluck('id', 'nombre_pozo');
    }

    public function collection(Collection $rows)
    {
        foreach($rows as $row) {
            $validatedData = validator($row->toArray(), [
                'nombre_pozo' => ['required', Rule::exists('pozos', 'nombre_pozo')],
            ])->validate();

            $pozoId = $this->pozos->get($validatedData['nombre_pozo']);

            $fechaAnalisis = Date::excelToDateTimeObject($row['fecha_analisis']);
            $fechaRecep = Date::excelToDateTimeObject($row['fecha_recep']);
            $fechaMuestreo = Date::excelToDateTimeObject($row['fecha_muestreo']);

            ComponentePozo::create([
                'dioxido_carbono' => $row['dioxido_carbono'],
                'pe_dioxido_carbono' => $row['pe_dioxido_carbono'],
                'mo_dioxido_carbono' => $row['mo_dioxido_carbono'],
                'den_dioxido_carbono' => $row['den_dioxido_carbono'],
                'acido_sulfidrico' => $row['acido_sulfidrico'],
                'pe_acido_sulfidrico' => $row['pe_acido_sulfidrico'],
                'mo_acido_sulfidrico' => $row['mo_acido_sulfidrico'],
                'den_acido_sulfidrico' => $row['den_acido_sulfidrico'],
                'nitrogeno' => $row['nitrogeno'],
                'pe_nitrogeno' => $row['pe_nitrogeno'],
                'mo_nitrogeno' => $row['mo_nitrogeno'],
                'den_nitrogeno' => $row['den_nitrogeno'],
                'metano' => $row['metano'],
                'pe_metano' => $row['pe_metano'],
                'mo_metano' => $row['mo_metano'],
                'den_metano' => $row['den_metano'],
                'etano' => $row['etano'],
                'pe_etano' => $row['pe_etano'],
                'mo_etano' => $row['mo_etano'],
                'den_etano' => $row['den_etano'],
                'propano' => $row['propano'],
                'pe_propano' => $row['pe_propano'],
                'mo_propano' => $row['mo_propano'],
                'den_propano' => $row['den_propano'],
                'iso_butano' => $row['iso_butano'],
                'pe_iso_butano' => $row['pe_iso_butano'],
                'mo_iso_butano' => $row['mo_iso_butano'],
                'den_iso_butano' => $row['den_iso_butano'],
                'n_butano' => $row['n_butano'],
                'pe_n_butano' => $row['pe_n_butano'],
                'mo_n_butano' => $row['mo_n_butano'],
                'den_n_butano' => $row['den_n_butano'],
                'iso_pentano' => $row['iso_pentano'],
                'pe_iso_pentano' => $row['pe_iso_pentano'],
                'mo_iso_pentano' => $row['mo_iso_pentano'],
                'den_iso_pentano' => $row['den_iso_pentano'],
                'n_pentano' => $row['n_pentano'],
                'pe_n_pentano' => $row['pe_n_pentano'],
                'mo_n_pentano' => $row['mo_n_pentano'],
                'den_n_pentano' => $row['den_n_pentano'],
                'n_exano' => $row['n_exano'],
                'pe_n_exano' => $row['pe_n_exano'],
                'mo_n_exano' => $row['mo_n_exano'],
                'den_n_exano' => $row['den_n_exano'], 
                'pozo_id' => $pozoId,
                'fecha_recep' => $fechaRecep,
                'fecha_analisis' => $fechaAnalisis,
                'no_determinacion' => $row['no_determinacion'],
                'equipo_utilizado' => $row['equipo_utilizado'],
                'met_laboratorio' => $row['met_laboratorio'],
                'observaciones' => $row['observaciones'],
                'nombre_componente' => $row['nombre_componente'],
                'fecha_muestreo' => $fechaMuestreo,
            ]);              
        }
    }
}
