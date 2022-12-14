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
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn('old_start_time');
            $table->dropColumn('old_end_time');
            $table->dropColumn('old_title');
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->timestamp('old_start_time')->nullable()->comment('thời gian bắt đầu cũ');
            $table->timestamp('old_end_time')->nullable()->comment('thời gian bắt đầu cũ');
            $table->text('old_title')->nullable()->comment('tiêu đề lịch trình cũ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedules', function (Blueprint $table) {
            //
        });
    }
};
