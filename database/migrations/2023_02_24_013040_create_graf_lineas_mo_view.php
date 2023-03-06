<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE VIEW graf_lineas_mo AS
            SELECT 'D-Carbono' AS Quimico, CAST(mo_dioxido_carbono AS decimal(32, 4)) AS Total_mo, nombre_componente AS nombre_componente, id AS idComPozo FROM componente_pozos UNION SELECT 'A-Sulf' AS Quimico, CAST(mo_acido_sulfidrico AS decimal(32,4)) AS Total_mo, nombre_componente AS nombre_componente, id AS idComPozo FROM componente_pozos UNION SELECT 'Nitrogeno' AS Quimico, CAST(mo_nitrogeno AS decimal(32,4)) AS Total_mo, nombre_componente AS nombre_componente, id AS idComPozo FROM componente_pozos UNION SELECT 'Metano' AS Quimico, CAST(mo_metano AS decimal(32,4)) AS Total_mo, nombre_componente AS nombre_componente, id AS idComPozo FROM componente_pozos UNION SELECT 'Etano' AS Quimico, CAST(mo_etano AS decimal(32,4)) AS Total_mo, nombre_componente AS nombre_componente, id AS idComPozo FROM componente_pozos UNION SELECT 'Propano' AS Quimico, CAST(mo_propano AS decimal(32,4)) AS Total_mo, nombre_componente AS nombre_componente, id AS idComPozo FROM componente_pozos UNION SELECT 'ISO-But' AS Quimico, CAST(mo_iso_butano AS decimal(32,4)) AS Total_mo, nombre_componente AS nombre_componente, id AS idComPozo FROM componente_pozos UNION SELECT 'N-Butano' AS Quimico, CAST(mo_n_butano AS decimal(32,4)) AS Total_mo, nombre_componente AS nombre_componente, id AS idComPozo FROM componente_pozos UNION SELECT 'ISO-Pe' AS Quimico, CAST(mo_iso_pentano AS decimal(32,4)) AS Total_mo, nombre_componente AS nombre_componente, id AS idComPozo FROM componente_pozos UNION SELECT 'N-Pe' AS Quimico, CAST(mo_n_pentano AS decimal(32,4)) AS Total_mo, nombre_componente AS nombre_componente, id AS idComPozo FROM componente_pozos UNION SELECT 'N-Exano' AS Quimico, CAST(mo_n_exano AS decimal(32,4)) AS Total_mo, nombre_componente AS nombre_componente, id AS idComPozo FROM componente_pozos
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('graf_lineas_mo');
    }
};
