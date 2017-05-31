<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

$component_table_name = 'credit_setting';

if (!Schema::hasColumn($component_table_name, 'name')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->string('name')->comment('积分动作');
    });
}

if (!Schema::hasColumn($component_table_name, 'alias')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->string('alias')->comment('积分名称');
    });
}


if (!Schema::hasColumn($component_table_name, 'type')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->string('type')->default('user')->comment('积分类型');
    });
}


if (!Schema::hasColumn($component_table_name, 'cycle')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->string('cycle')->default('')->comment('周期范围');
    });
}

if (!Schema::hasColumn($component_table_name, 'cycle_times')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('cycle_times')->default(1)->comment('周期内最多奖励次数');
    });
}

if (!Schema::hasColumn($component_table_name, 'des')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->text('des')->comment('描述');
    });
}

if (!Schema::hasColumn($component_table_name, 'info')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->text('info')->comment('积分说明');
    });
}

if (!Schema::hasColumn($component_table_name, 'score')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('score')->default(0)->comment('得分');
    });
}