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
        Schema::create('element_schedules', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable()->comment('id user');
            $table->bigInteger('schedule_id')->nullable()->comment('id schedule');
            $table->text('name')->nullable()->comment('Tên thành phần');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('element_schedules', function (Blueprint $table) {
            //
        });
    }
};
