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
        Schema::table('essentials_payroll_groups', function (Blueprint $table) {
            $table->string('payroll_group_month')->nullable();
            $table->string('payroll_group_year')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('essentials_payroll_groups', function (Blueprint $table) {
            $table->dropColumn('payroll_group_month');
            $table->dropColumn('payroll_group_year');
        });
    }
};
