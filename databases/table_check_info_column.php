<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

$component_table_name = 'check_info';

if (!Schema::hasColumn($component_table_name, 'user_id')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('user_id')->comment('用户ID');
    });
}

if (!Schema::hasColumn($component_table_name, 'con_num')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('con_num')->default(0)->comment('连续签到次数');
    });
}


if (!Schema::hasColumn($component_table_name, 'total_num')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('total_num')->default(0)->comment('总签到次数');
    });
}