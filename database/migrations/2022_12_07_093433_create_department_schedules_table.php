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

        //đây là bảng những phòng ban nào cần tham gia
        Schema::create('department_schedules', function (Blueprint $table) {
            $table->timestamp('departments_id')->nullable()->comment('id phòng ban');
            $table->timestamp('schedules_id')->nullable()->comment('id lịch');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('department_schedules', function (Blueprint $table) {
            //
        });
    }
};
