<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCBuildingTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_building_types', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('is_deleted')->nullable();
            $table->timestamp('last_modified_on')->nullable();
            $table->integer('version')->nullable();
            $table->integer('sort_id')->nullable();
            $table->string('name_arm', 500)->nullable();
            $table->string('name_eng', 500)->nullable();
            $table->string('name_ru', 500)->nullable();
            $table->string('name_ar', 500)->nullable();
            $table->integer('last_modified_by')->nullable();
            $table->string('comment', 500);
            $table->integer('created_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
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
        Schema::dropIfExists('c_building_types');
    }
}
