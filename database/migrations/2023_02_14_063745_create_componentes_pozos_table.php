<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::create('componentes_pozos', function (Blueprint $table) {
            $table->id();
            $table->string('dioxido_carbono', 50);
            $table->string('pe_dioxido_carbono', 50);
            $table->string('mo_dioxido_carbono', 50);
            $table->string('den_dioxido_carbono', 50);
            $table->string('acido_sulfidrico', 50);
            $table->string('pe_acido_sulfidrico', 50);
            $table->string('mo_acido_sulfidrico', 50);
            $table->string('den_acido_sulfidrico', 50);
            $table->string('nitrogeno', 32);
            $table->string('pe_nitrogeno', 50);
            $table->string('mo_nitrogeno', 50);
            $table->string('den_nitrogeno', 50);
            $table->string('metano', 32);
            $table->string('pe_metano', 50);
            $table->string('mo_metano', 50);
            $table->string('den_metano', 50);
            $table->string('etano', 32);
            $table->string('pe_etano', 50);
            $table->string('mo_etano', 50);
            $table->string('den_etano', 50);
            $table->string('propano', 32);
            $table->string('pe_propano', 50);
            $table->string('mo_propano', 50);
            $table->string('den_propano', 50);
            $table->string('iso_butano', 32);
            $table->string('pe_iso_butano', 50);
            $table->string('mo_iso_butano', 50);
            $table->string('den_iso_butano', 50);
            $table->string('n_butano', 32);
            $table->string('pe_n_butano', 50);
            $table->string('mo_n_butano', 50);
            $table->string('den_n_butano', 50);
            $table->string('iso_pentano', 32);
            $table->string('pe_iso_pentano', 50);
            $table->string('mo_iso_pentano', 50);
            $table->string('den_iso_pentano', 50);
            $table->string('n_pentano', 32);
            $table->string('pe_n_pentano', 50);
            $table->string('mo_n_pentano', 50);
            $table->string('den_n_pentano', 50);
            $table->string('n_exano', 32);
            $table->string('pe_n_exano', 50);
            $table->string('mo_n_exano', 50);
            $table->string('den_n_exano', 50);
            $table->foreignId('pozo_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->date('fecha_recep');
            $table->date('fecha_analisis');
            $table->string('no_determinacion', 100);
            $table->string('equipo_utilizado', 100);
            $table->string('met_laboratorio');
            $table->text('observaciones')->nullable();
            $table->string('nombre_componente', 100);
            $table->date('fecha_muestreo')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('componentes_pozos');
    }
};
