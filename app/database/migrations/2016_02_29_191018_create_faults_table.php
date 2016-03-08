<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFaultsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faults', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('description');
            $table->string('operating_system');
            $table->enum('type', array('pc', 'lan'));
            $table->enum('state', array('registered', 'inProgress', 'fixed'));
            $table->dateTime('started_fixing');
            $table->dateTime('finished_fixing');
            $table->timestamps();
            $table->integer('customer_id')->index();
            $table->integer('employee_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('faults');
    }
}
