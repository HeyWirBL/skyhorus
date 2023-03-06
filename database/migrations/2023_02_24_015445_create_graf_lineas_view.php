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
            CREATE VIEW graf_lineas AS
            SELECT 'D-Carbono' AS Quimico, CAST(dioxido_carbono AS decimal(32, 4)) AS Total, nombre_componente AS nombre_componente, id AS idComPozo FROM componente_pozos UNION SELECT 'A-Sulf' AS Quimico, CAST(acido_sulfidrico AS decimal(32,4)) AS Total, nombre_componente AS nombre_componente, id AS idComPozo FROM componente_pozos UNION SELECT 'Nitrogeno' AS Quimico, CAST(nitrogeno AS decimal(32,4)) AS Total, nombre_componente AS nombre_componente, id AS idComPozo FROM componente_pozos UNION SELECT 'Metano' AS Quimico, CAST(metano AS decimal(32,4)) AS Total, nombre_componente AS nombre_componente, id AS idComPozo FROM componente_pozos UNION SELECT 'Etano' AS Quimico, CAST(etano AS decimal(32,4)) AS Total, nombre_componente AS nombre_componente, id AS idComPozo FROM componente_pozos UNION SELECT 'Propano' AS Quimico, CAST(propano AS decimal(32,4)) AS Total, nombre_componente AS nombre_componente, id AS idComPozo FROM componente_pozos UNION SELECT 'ISO-But' AS Quimico, CAST(iso_butano AS decimal(32,4)) AS Total, nombre_componente AS nombre_componente, id AS idComPozo FROM componente_pozos UNION SELECT 'N-Butano' AS Quimico, CAST(n_butano AS decimal(32,4)) AS Total, nombre_componente AS nombre_componente, id AS idComPozo FROM componente_pozos UNION SELECT 'ISO-Pe' AS Quimico, CAST(iso_pentano AS decimal(32,4)) AS Total, nombre_componente AS nombre_componente, id AS idComPozo FROM componente_pozos UNION SELECT 'N-Pe' AS Quimico, CAST(n_pentano AS decimal(32,4)) AS Total, nombre_componente AS nombre_componente, id AS idComPozo FROM componente_pozos UNION SELECT 'N-Exano' AS Quimico, CAST(n_exano AS decimal(32,4)) AS Total, nombre_componente AS nombre_componente, id AS idComPozo FROM componente_pozos
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('graf_lineas');
    }
};
