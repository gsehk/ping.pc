<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

$component_table_name = 'credit_record';

if (!Schema::hasColumn($component_table_name, 'cid')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('cid')->default(0)->comment('对应的积分设置ID');
    });
}

if (!Schema::hasColumn($component_table_name, 'type')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->tinyInteger('type')->default(1)->comment('类型 1-正常变更 2-充值 3-转账');
    });
}


if (!Schema::hasColumn($component_table_name, 'user_id')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('user_id')->default(NULL)->comment('用户ID');
    });
}


if (!Schema::hasColumn($component_table_name, 'action')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->string('action')->default(NULL)->comment('操作');
    });
}

if (!Schema::hasColumn($component_table_name, 'des')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->text('des')->default(NULL)->comment('详情');
    });
}

if (!Schema::hasColumn($component_table_name, 'change')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->string('change')->default(NULL)->comment('积分变更');
    });
}

if (!Schema::hasColumn($component_table_name, 'detail')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->string('detail')->default(NULL)->comment('API所需描述');
    });
}

if (!Schema::hasColumn($component_table_name, 'reason')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->string('reason')->default(NULL)->comment('驳回理由');
    });
}