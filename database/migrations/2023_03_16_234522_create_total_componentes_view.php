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
            CREATE VIEW total_componentes AS
            SELECT CAST(dioxido_carbono AS decimal(32, 4)) + CAST(acido_sulfidrico AS decimal(32, 4)) + CAST(nitrogeno AS decimal(32, 4)) + CAST(metano AS decimal(32, 4)) + CAST(etano AS decimal(32, 4)) + CAST(propano AS decimal(32, 4)) + CAST(iso_butano AS decimal(32, 4)) + CAST(n_butano AS decimal(32, 4)) + CAST(iso_pentano AS decimal(32, 4)) + CAST(n_pentano AS decimal(32, 4)) + CAST(n_exano AS decimal(32, 4)) AS Total_PM, CAST(pe_dioxido_carbono AS decimal(32, 4)) + CAST(pe_acido_sulfidrico AS decimal(32, 4)) + CAST(pe_nitrogeno AS decimal(32, 4)) + CAST(pe_metano AS decimal(32, 4)) + CAST(pe_etano AS decimal(32, 4)) + CAST(pe_propano AS decimal(32, 4)) + CAST(pe_iso_butano AS decimal(32, 4)) + CAST(pe_n_butano AS decimal(32, 4)) + CAST(pe_iso_pentano AS decimal(32, 4)) + CAST(pe_n_pentano AS decimal(32, 4)) + CAST(pe_n_exano AS decimal(32, 4)) AS Total_Peso, CAST(mo_dioxido_carbono AS decimal(32, 4)) + CAST(mo_acido_sulfidrico AS decimal(32, 4)) + CAST(mo_nitrogeno AS decimal(32, 4)) + CAST(mo_metano AS decimal(32, 4)) + CAST(mo_etano AS decimal(32, 4)) + CAST(mo_propano AS decimal(32, 4)) + CAST(mo_iso_butano AS decimal(32, 4)) + CAST(mo_n_butano AS decimal(32, 4)) + CAST(mo_iso_pentano AS decimal(32, 4)) + CAST(mo_n_pentano AS decimal(32, 4)) + CAST(mo_n_exano AS decimal(32, 4)) AS Total_MOL, CAST(den_dioxido_carbono AS decimal(32, 4)) + CAST(den_acido_sulfidrico AS decimal(32, 4)) + CAST(den_nitrogeno AS decimal(32, 4)) + CAST(den_metano AS decimal(32, 4)) + CAST(den_etano AS decimal(32, 4)) + CAST(den_propano AS decimal(32, 4)) + CAST(den_iso_butano AS decimal(32, 4)) + CAST(den_n_butano AS decimal(32, 4)) + CAST(den_iso_pentano AS decimal(32, 4)) + CAST(den_n_pentano AS decimal(32, 4)) + CAST(den_n_exano AS decimal(32, 4)) AS Total_Densidad, id AS idComPozo FROM componente_pozos
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('total_componentes');
    }
};
