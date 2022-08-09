<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('name');
            $table->string('type');
            $table->string('mana_cost');
            $table->json('image_uris');
            $table->json('legalities');
            $table->date('released_at');
            $table->string('set_name');
            $table->text('oracle_text');
            $table->timestamps();
            $table->string('layout');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cards');
    }
};
