<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

$component_table_name = 'denounce';

if (!Schema::hasColumn($component_table_name, 'from')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->string('from')->comment('资源来源位置');
    });
}

if (!Schema::hasColumn($component_table_name, 'aid')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('aid')->comment('资源ID');
    });
}

if (!Schema::hasColumn($component_table_name, 'state')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->tinyInteger('state')->default('0')->comment('状态');
    });
}

if (!Schema::hasColumn($component_table_name, 'user_id')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('user_id')->comment('举报人');
    });
}

if (!Schema::hasColumn($component_table_name, 'to_uid')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('to_uid')->comment('被举报人');
    });
}

if (!Schema::hasColumn($component_table_name, 'reason')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->string('reason')->default(NULL)->comment('举报原因');
    });
}

if (!Schema::hasColumn($component_table_name, 'source_url')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->string('source_url')->default(NULL)->comment('资源来源页面url');
    });
}