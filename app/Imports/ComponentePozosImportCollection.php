<?php

namespace App\Imports;

use App\Models\ComponentePozo;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ComponentePozosImportCollection implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public $pozoId;
    public $fechaRecep;
    public $fechaAnalisis;
    public $fechaMuest;

    public function __construct($pozoId, $fechaRecep, $fechaAnalisis, $fechaMuest)
    {
        $this->pozoId = $pozoId;
        $this->fechaRecep = $fechaRecep;
        $this->fechaAnalisis = $fechaAnalisis;
        $this->fechaMuest = $fechaMuest;
    }

    public function collection(Collection $rows)
    {
        foreach($rows as $row)
        {
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
                'pozo_id' => $this->pozoId,
                'fecha_recep' => $this->fechaRecep,
                'fecha_analisis' => $this->fechaAnalisis,
                'no_determinacion' => $row['no_determinacion'],
                'equipo_utilizado' => $row['equipo_utilizado'],
                'met_laboratorio' => $row['met_laboratorio'],
                'observaciones' => $row['observaciones'],
                'nombre_componente' => $row['nombre_componente'],
                'fecha_muestreo' => $this->fechaMuest,
            ]);
        }
    }
}
