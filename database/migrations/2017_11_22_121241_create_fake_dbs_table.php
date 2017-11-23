<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFakeDbsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fake_dbs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('Name');
            $table->string('Sex', 250);
            $table->date( 'Birthday');
            $table->string('Phone number', 250);
            $table->string('Adress', 250);
            $table->string('Country', 250);
            $table->string('Email', 250);
            $table->float('Salary', 250);
            $table->string('Profession', 250);
            $table->integer('Professional Level (1-5)');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fake_dbs');
    }
}
