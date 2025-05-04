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
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('work_duration')->nullable();
            $table->string('total_work_duration')->nullable();
            $table->string('total_leaves')->nullable();
            $table->string('total_absent')->nullable();
            $table->string('total_days_worked')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('work_duration');
            $table->dropColumn('total_work_duration');
            $table->dropColumn('total_leaves');
            $table->dropColumn('total_absent');
            $table->dropColumn('total_days_worked');
        });
    }
};
