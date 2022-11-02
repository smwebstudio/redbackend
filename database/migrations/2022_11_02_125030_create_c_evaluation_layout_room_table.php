<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCEvaluationLayoutRoomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_evaluation_layout_room', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->char('sort_id', 20)->nullable();
            $table->string('name_arm', 45)->nullable();
            $table->string('name_eng', 45)->nullable();
            $table->string('name_ru', 45)->nullable();
            $table->string('name_ar', 45)->nullable();
            $table->decimal('coefficient', 4)->nullable();
            $table->date('last_modified_on')->nullable();
            $table->integer('last_modified_by')->nullable();
            $table->integer('created_by')->nullable();
            $table->dateTime('created_on')->nullable();
            $table->integer('evaluation_location_type_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('c_evaluation_layout_room');
    }
}
