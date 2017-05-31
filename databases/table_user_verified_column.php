<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

$component_table_name = 'user_verified';

if (!Schema::hasColumn($component_table_name, 'user_id')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('user_id')->comment('用户ID');
    });
}

if (!Schema::hasColumn($component_table_name, 'realname')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->string('realname')->default('')->comment('真实姓名');
    });
}

if (!Schema::hasColumn($component_table_name, 'idcard')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->string('idcard')->default('')->comment('证件号码');
    });
}

if (!Schema::hasColumn($component_table_name, 'phone')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->string('phone')->default('')->comment('联系方式');
    });
}

if (!Schema::hasColumn($component_table_name, 'info')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->string('info')->default('')->comment('补充认证资料');
    });
}

if (!Schema::hasColumn($component_table_name, 'verified')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->tinyInteger('verified')->default('0')->comment('认证状态，0：未认证；1：成功 2 :  失败');
    });
}

if (!Schema::hasColumn($component_table_name, 'storage')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('storage')->default(NULL)->comment('认证资料存储id');
    });
}