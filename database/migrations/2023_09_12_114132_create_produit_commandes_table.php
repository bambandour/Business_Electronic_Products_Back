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
        Schema::create('produit_commandes', function (Blueprint $table) {
            $table->id();
            $table->integer('quantite');
            $table->float('prix');
            $table->float('reduction')->default(0);
            // $table->foreignIdFor(\App\Models\Commande::class)->constrained('commandes')->cascadeOnDelete();
            // $table->foreignIdFor(\App\Models\ProduitSuccursale::class)->constrained('produit_succurales')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produit_commandes');
    }
};
