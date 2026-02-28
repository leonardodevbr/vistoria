<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inspection_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_id')->constrained()->onDelete('cascade');
            $table->string('categoria')->nullable();
            $table->string('item');
            $table->string('marca_modelo')->nullable();
            $table->string('localizacao');
            $table->enum('estado_fisico', ['Novo', 'Seminovo', 'Ótimo', 'Bom', 'Regular']);
            $table->enum('funcionamento', [
                'Funcionando perfeitamente', 
                'Funcionando', 
                'Funcionando com ressalvas', 
                'Não testado', 
                'Não funciona',
                'Não se aplica'
            ]);
            $table->text('observacoes')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_items');
    }
};
