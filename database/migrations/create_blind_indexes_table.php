<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('blind_indexes', function (Blueprint $table) {
            $table->id();

            $table->string('type');
            $table->string('value');
            $table->unsignedBigInteger('foreign_id');

            $table->index(['type', 'value']);
            $table->unique(['type', 'foreign_id']);
        });
    }

    public function down()
    {
        Schema::drop('blind_indexes');
    }
};
