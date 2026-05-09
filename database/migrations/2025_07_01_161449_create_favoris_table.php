<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFavorisTable extends Migration
{
    public function up()
    {
        Schema::create('favoris', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('chambre_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->unique(['user_id', 'chambre_id']); // Assure qu'un utilisateur ne peut ajouter une chambre qu'une fois
        });
    }

    public function down()
    {
        Schema::dropIfExists('favoris');
    }
}