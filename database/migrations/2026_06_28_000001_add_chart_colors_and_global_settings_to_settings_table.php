<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('chart_income_color', 7)->default('#22c55e')->after('primary_color');
            $table->string('chart_expense_color', 7)->default('#f43f5e')->after('chart_income_color');

            $table->dropForeign(['user_id']);
            $table->dropUnique(['user_id']);
            $table->foreignId('user_id')->nullable()->change();
            $table->unique(['user_id']);
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['chart_income_color', 'chart_expense_color']);

            $table->dropForeign(['user_id']);
            $table->dropUnique(['user_id']);
            $table->foreignId('user_id')->nullable(false)->change();
            $table->unique(['user_id']);
        });
    }
};
