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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->timestamp('start_time')->comment('thời gian bắt đầu');
            $table->timestamp('end_time')->comment('thời gian bắt đầu');
            $table->timestamp('location_id')->nullable()->comment('id địa điểm');
            $table->text('location_other_name')->nullable()->comment('tên địa điểm không có trong db');
            $table->bigInteger('leader_id')->nullable()->comment('id bảng user, người chủ trì');
            $table->text('leader_orther_name')->nullable()->comment('tên chủ trì không có trong db users');
            $table->text('title')->comment('tiêu đề lịch trình');
            $table->text('description')->nullable()->comment('chi tiết lịch trình');
            $table->integer('type')->default(0)->comment('0: lịch học viện, 1: lịch phòng ban');
            $table->bigInteger('department_id')->nullable()->comment('id bảng department');
            $table->bigInteger('create_by')->nullable()->comment('id bảng user, người tạo');
            $table->bigInteger('update_by')->nullable()->comment('id bảng user, người cập nhật');
            $table->bigInteger('close_by')->nullable()->comment('id bảng user, người xóa');
            $table->integer('status')->default(0)->comment('mới tạo, đã duyệt,...');
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
        Schema::table('schedules', function (Blueprint $table) {
            //
        });
    }
};
