<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('ssh_keys', function (Blueprint $table) {
            $table->id();

            $table->string('name')->unique();

            $table->longText('private_key');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ssh_keys');
    }
};
