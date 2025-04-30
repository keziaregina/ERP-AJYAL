<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Contracts\Permission as ContractsPermission;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $data = [
            ['name' => 'essentials.show_overtime_page'],
        ];

        foreach ($data as $d) {
            Permission::updateOrCreate($d);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $data = [
            ['name' => 'essentials.show_overtime_page'],
        ];

        foreach ($data as $d) {
            Permission::where('name', $d['name'])->delete();
        }
    }
};
