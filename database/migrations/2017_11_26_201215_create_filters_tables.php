<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFiltersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phoneNumber', function (Blueprint $table) {
            $table->increments('id');
            $table->string("type");
            $table->string("filter");
            $table->string("filterType");
            $table->integer("userId");
            $table->integer("savedId");
        });
        Schema::create('name', function (Blueprint $table) {
            $table->increments('id');
            $table->string("type");
            $table->string("filter");
            $table->string("filterType");
            $table->integer("userId");
            $table->integer("savedId");
        });
        Schema::create('email', function (Blueprint $table) {
            $table->increments('id');
            $table->string("type");
            $table->string("filter");
            $table->string("filterType");
            $table->integer("userId");
            $table->integer("savedId");
        });
        Schema::create('adress', function (Blueprint $table) {
            $table->increments('id');
            $table->string("type");
            $table->string("filter");
            $table->string("filterType");
            $table->integer("userId");
            $table->integer("savedId");
        });
        Schema::create('salary', function (Blueprint $table) {
            $table->increments('id');
            $table->string("type");
            $table->float("filter");
            $table->float("filterTo");
            $table->string("filterType");
            $table->integer("userId");
            $table->integer("savedId");
        });
        Schema::create('birthday', function (Blueprint $table) {
            $table->increments('id');
            $table->date("dateTo");
            $table->date("dateFrom");
            $table->string("type");
            $table->string("filterType");
            $table->integer("userId");
            $table->integer("savedId");
        });
        Schema::create('saved', function (Blueprint $table) {
            $table->increments('id');
            $table->string("savedName");
            $table->integer("userId");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('phoneNumber');
        Schema::dropIfExists('birthday');
        Schema::dropIfExists('salary');
        Schema::dropIfExists('adress');
        Schema::dropIfExists('email');
        Schema::dropIfExists('name');
        Schema::dropIfExists('saved');
    }
}
