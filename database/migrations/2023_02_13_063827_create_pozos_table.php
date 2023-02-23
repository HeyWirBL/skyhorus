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
        Schema::create('pozos', function (Blueprint $table) {
            $table->id();
            $table->string('punto_muestreo', 150);
            $table->date('fecha_hora');
            $table->string('identificador', 150)->nullable();
            $table->string('presion_kgcm2', 150);
            $table->string('presion_psi', 150);
            $table->string('temp_C', 150);
            $table->string('temp_F', 150);
            $table->string('volumen_cm3', 150);
            $table->string('volumen_lts', 150);
            $table->string('observaciones', 150)->nullable();
            $table->string('nombre_pozo', 150);
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
        Schema::dropIfExists('pozos');
    }
};
