<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMarcaToCarrosTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('carros', function (Blueprint $table) {
            $table->smallInteger('marca_id')->unsigned();

            $table->foreign('marca_id')
                    ->references('id')->on('marcas')
                    ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('carros', function (Blueprint $table) {
            //
        });
    }

}
