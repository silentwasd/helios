<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->foreignId('ssh_key_id')
                  ->after('username')
                  ->constrained()
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->bigInteger('port')
                  ->unsigned()
                  ->default(22)
                  ->after('host');
        });
    }

    public function down()
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('ssh_key_id');

            $table->dropColumn('port');
        });
    }
};
