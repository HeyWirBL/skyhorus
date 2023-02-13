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
        Schema::create('documentos', function (Blueprint $table) {
            $table->id('idDocumento');
            $table->string('Nombre', 50);
            $table->mediumText('documento');
            $table->foreignId('idDirectorio')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->foreignId('idAno')
                  ->constrained()
                  ->cascadeOnDelete();     
            $table->foreignId('idMesdet')
                  ->constrained()
                  ->cascadeOnDelete();
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
        Schema::dropIfExists('documentos');
    }
};
