<?php 

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeFaultTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('employee_fault_type', function($table) {
            $table->integer('employee_id')->index();
            $table->integer('fault_type_id')->index();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
       Schema::drop('employee_fault_type');
    }

}