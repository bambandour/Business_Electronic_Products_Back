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
        Schema::create('produit_succursales', function (Blueprint $table) {
            $table->id();
            $table->integer('quantite');
            $table->float('prix');
            $table->float('prixEnGros')->nullable();
            // $table->foreignIdFor(\App\Models\Produit::class)->constrained('produits')->cascadeOnDelete();
            // $table->foreignIdFor(\App\Models\Succursale::class)->constrained('succurales')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produit_succursales');
    }
};
