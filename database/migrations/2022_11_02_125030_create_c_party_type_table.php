<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCPartyTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_party_type', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('name_arm', 200)->nullable();
            $table->string('name_eng', 200)->nullable();
            $table->string('name_ru', 200)->nullable();
            $table->string('name_ar', 200)->nullable();
            $table->char('sort_id', 20)->nullable();
            $table->integer('version')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->date('last_modified_on')->nullable();
            $table->integer('last_modified_by')->nullable();
            $table->integer('created_by')->nullable();
            $table->dateTime('created_on')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('c_party_type');
    }
}
