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
        Schema::table('projects', 
        function (Blueprint $table) {
            // AGGIUNGE L'ID DI TYPE DOPO LA COLONNA ID
            $table->unsignedBiginteger('type_id')->nullable()->after('id');

            $table->foreign('type_id') // ASSEGNA LA FK type_id legata al TYPE
                ->references('id') // LEGATA AL CAMPO id
                ->on('types'); // DELLA TABELLA types
        }
    );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign('projects_type_id_foreign'); // ELIMINA LA FK type_id DALLA TABELLA projects
            $table->dropColumn('type_id'); // ELIMINA LA COLONNA type_id
        });
    }
};
