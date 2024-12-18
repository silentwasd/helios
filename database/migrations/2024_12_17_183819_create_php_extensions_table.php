<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('php_extensions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('program_id')
                  ->constrained()
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->string('name');
            $table->string('status')->default('not-installed');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('php_extensions');
    }
};
