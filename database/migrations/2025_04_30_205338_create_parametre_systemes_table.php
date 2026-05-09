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
        Schema::create('parametres_systeme', function (Blueprint $table) {
            $table->id();
            $table->string('cle', 100)->unique();
            $table->text('valeur');
            $table->text('description')->nullable();
            $table->enum('type_parametre', ['systeme', 'application', 'affichage', 'notification'])->default('application');
            $table->boolean('modifiable')->default(true);
            $table->timestamp('date_modification')->useCurrent();
            $table->foreignId('id_admin_modification')->nullable()->constrained('administrateurs');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametre_systemes');
    }
};
