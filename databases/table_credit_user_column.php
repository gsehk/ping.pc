<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

$component_table_name = 'credit_user';

if (!Schema::hasColumn($component_table_name, 'user_id')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('user_id')->comment('用户ID');
    });
}

if (!Schema::hasColumn($component_table_name, 'score')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('score')->comment('积分');
    });
}