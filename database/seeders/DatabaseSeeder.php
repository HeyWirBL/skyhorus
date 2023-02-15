<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory()->create([
            'nombre' => 'Jesús Manuel',
            'apellidos' => 'Méndez García',
            'usuario' => 'Jesus.Mendez',
            'email' => 'jesus@admin.com',
            'password' => Hash::make('Desarrollo123#2023'),
            'rol' => 'Administrador',
        ]);

        \App\Models\Directorio::factory(3)->create();

        \App\Models\Ano::factory(2)->create();

        $pozo = \App\Models\Pozo::factory()->create([
            'punto_muestreo' => 'SALIDA DE LÍNENA DE GAS',
            'fecha_hora' => '2021-03-18',
            'identificador' => 'E-GGSOL-2021-0081',
            'presion_kgcm2' => '16.7',
            'presion_psi' => '244.568',
            'temp_C' => '34',
            'temp_F' => '93',
            'volumen_cm3' => '300',
            'volumen_lts' => '0.3',
            'nombre_pozo' => 'ÉBANO 1063 H',
        ]);

        \App\Models\ComponentesPozo::factory()->create([
            'dioxido_carbono' => '44.01',
            'pe_dioxido_carbono' => '2.0165',
            'mo_dioxido_carbono' => '0.8604',
            'den_dioxido_carbono' => '0.0019',
            'acido_sulfidrico' => '34.08',
            'pe_acido_sulfidrico' => '0',
            'mo_acido_sulfidrico' => '0',
            'den_acido_sulfidrico' => '0.0014',
            'nitrogeno' => '28.013',
            'pe_nitrogeno' => '0.9407',
            'mo_nitrogeno' => '0.6306',
            'den_nitrogeno' => '0.0012',
            'metano' => '16.043',
            'pe_metano' => '74.9923',
            'mo_metano' => '87.7841',
            'den_metano' => '0.0007',
            'etano' => '30.07',
            'pe_etano' => '9.8863',
            'mo_etano' => '6.1743',
            'den_etano' => '0.0013',
            'propano' => '44.097',
            'pe_propano' => '6.9328',
            'mo_propano' => '2.9525',
            'den_propano' => '0.0019',
            'iso_butano' => '58.123',
            'pe_iso_butano' => '1.7434',
            'mo_iso_butano' => '0.5633',
            'den_iso_butano' => '0.0025',
            'n_butano' => '58.123',
            'pe_n_butano' => '2.1218',
            'mo_n_butano' => '0.6856',
            'den_n_butano' => '0.0025',
            'iso_pentano' => '72.15',
            'pe_iso_pentano' => '0.6872',
            'mo_iso_pentano' => '0.1789',
            'den_iso_pentano' => '0.0031',
            'n_pentano' => '72.15',
            'pe_n_pentano' => '0.5045',
            'mo_n_pentano' => '0.1313',
            'den_n_pentano' => '0.0031',
            'n_exano' => '84',
            'pe_n_exano' => '0.1745',
            'mo_n_exano' => '0.039',
            'den_n_exano' => '0.0036',
            'pozo_id' => $pozo->id,
            'fecha_recep' => '2022-12-06',
            'fecha_analisis' => '2022-12-06',
            'no_determinacion' => '10',
            'equipo_utilizado' => 'CROMATÓGRAFO Agilent 6890',
            'met_laboratorio' => 'Basado en la Norma ASTM D 1945-14 (2019), Standard Test Method for Analysis of Natural Gas by Gas Ch',
            'observaciones' => '* DATOS CALCULADOS. ** EL PESO MOLECULAR DEL PSEUDOCOMPONENTE C6 ESTA REFERENCIADO DE LA TABLA DE KATZ Y FIROOZABADI DEL LIBRO PHASE BEHAVIOR pag 71.',
            'nombre_componente' => 'Nom Componente',
            'fecha_muestreo' => '2021-07-04',
        ]);
    }
}
