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
            $table->boolean('is_public')->default(true)->comment('lich co public khong');
            $table->timestamp('old_start_time')->comment('thời gian bắt đầu cũ');
            $table->timestamp('old_end_time')->comment('thời gian bắt đầu cũ');
            $table->timestamp('old_location_id')->nullable()->comment('id địa điểm cũ');
            $table->text('old_location_other_name')->nullable()->comment('tên địa điểm không có trong db cũ');
            $table->bigInteger('old_leader_id')->nullable()->comment('id bảng user, người chủ trì cũ');
            $table->text('old_leader_orther_name')->nullable()->comment('tên chủ trì không có trong db users cũ');
            $table->text('old_title')->comment('tiêu đề lịch trình cũ');
            $table->text('old_description')->nullable()->comment('chi tiết lịch trình cũ');
            $table->integer('old_type')->default(0)->comment('0: lịch học viện, 1: lịch phòng ban cũ');
            $table->bigInteger('old_department_id')->nullable()->comment('id bảng department cũ');
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
