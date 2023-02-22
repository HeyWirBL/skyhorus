<?php

namespace App\Imports;

use App\Models\ComponentePozo;
use Maatwebsite\Excel\Concerns\ToModel;

class ComponentePozosImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return |null
    */
    public function model(array $row)
    {
        return new ComponentePozo([
            'dioxido_carbono' => $row[0],
            'pe_dioxido_carbono' => $row[1],
            'mo_dioxido_carbono' => $row[2],
            'den_dioxido_carbono' => $row[3],
            'acido_sulfidrico' => $row[4],
            'pe_acido_sulfidrico' => $row[5],
            'mo_acido_sulfidrico' => $row[6],
            'den_acido_sulfidrico' => $row[7],
            'nitrogeno' => $row[8],
            'pe_nitrogeno' => $row[9],
            'mo_nitrogeno' => $row[10],
            'den_nitrogeno' => $row[11],
            'metano' => $row[12],
            'pe_metano' => $row[13],
            'mo_metano' => $row[14],
            'den_metano' => $row[15],
            'etano' => $row[16],
            'pe_etano' => $row[17],
            'mo_etano' => $row[18],
            'den_etano' => $row[19],
            'propano' => $row[20],
            'pe_propano' => $row[21],
            'mo_propano' => $row[22],
            'den_propano' => $row[23],
            'iso_butano' => $row[24],
            'pe_iso_butano' => $row[25],
            'mo_iso_butano' => $row[26],
            'den_iso_butano' => $row[27],
            'n_butano' => $row[28],
            'pe_n_butano' => $row[29],
            'mo_n_butano' => $row[30],
            'den_n_butano' => $row[31],
            'iso_pentano' => $row[32],
            'pe_iso_pentano' => $row[33],
            'mo_iso_pentano' => $row[34],
            'den_iso_pentano' => $row[35],
            'n_pentano' => $row[36],
            'pe_n_pentano' => $row[37],
            'mo_n_pentano' => $row[38],
            'den_n_pentano' => $row[39],
            'n_exano' => $row[40],
            'pe_n_exano' => $row[41],
            'mo_n_exano' => $row[42],
            'den_n_exano' => $row[43], 
            'pozo_id' => '1',
            'fecha_recep' => $row[44],
            'fecha_analisis' => $row[45],
            'no_determinacion' => $row[46],
            'equipo_utilizado' => $row[47],
            'met_laboratorio' => $row[48],
            'observaciones' => $row[49],
            'nombre_componente' => $row[50],
            'fecha_muestreo' => $row[51],
        ]);
    }
}
