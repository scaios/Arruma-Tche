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
    Schema::table('complaints', function (Blueprint $table) {
        // Adiciona a coluna para a latitude, pode ser nula
        $table->decimal('latitude', 10, 8)->nullable()->after('address');
        // Adiciona a coluna para a longitude, pode ser nula
        $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
    });
}

public function down(): void
{
    Schema::table('complaints', function (Blueprint $table) {
        $table->dropColumn(['latitude', 'longitude']);
    });
}
};
