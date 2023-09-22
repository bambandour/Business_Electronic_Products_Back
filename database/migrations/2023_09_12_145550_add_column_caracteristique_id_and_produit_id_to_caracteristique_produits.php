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
        Schema::table('caracteristique_produits', function (Blueprint $table) {
             $table->foreignIdFor(\App\Models\Produit::class)->constrained()->cascadeOnDelete();
             $table->foreignIdFor(\App\Models\Caracteristique::class)->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('caracteristique_produits', function (Blueprint $table) {
            //
        });
    }
};
