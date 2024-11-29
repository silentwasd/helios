<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ssh_keys', function (Blueprint $table) {
            $table->foreignId('user_id')
                  ->after('id')
                  ->constrained()
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('ssh_keys', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
