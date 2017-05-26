<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

$component_table_name = 'user_visitor';

if (!Schema::hasColumn($component_table_name, 'user_id')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('user_id')->comment('访问用户ID');
    });
}

if (!Schema::hasColumn($component_table_name, 'score')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('to_uid')->comment('被访问者用户ID');
    });
}