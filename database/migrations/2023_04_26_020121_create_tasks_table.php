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
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('priority')->unsigned();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('projectid')->unsigned()->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('user_id');
            $table->index('projectid');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('projectid')->references('id')->on('projects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
