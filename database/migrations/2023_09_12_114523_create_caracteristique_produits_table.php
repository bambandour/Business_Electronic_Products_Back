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
        Schema::create('caracteristique_produits', function (Blueprint $table) {
            $table->id();
            $table->string('description')->nullable();
            $table->string('valeur')->nullable();
            // $table->foreignIdFor(\App\Models\Produit::class)->constrained('produits')->cascadeOnDelete();
            // $table->foreignIdFor(\App\Models\Caracteristique::class)->constrained('caracteristiques')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caracteristique_produits');
    }
};
