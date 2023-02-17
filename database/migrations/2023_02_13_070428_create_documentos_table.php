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
            $table->id();
            $table->string('Nombre', 50);
            $table->mediumText('documento');
            $table->foreignId('directorio_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->foreignId('ano_id')
                  ->constrained()
                  ->cascadeOnDelete();     
            $table->foreignId('mes_id')
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
