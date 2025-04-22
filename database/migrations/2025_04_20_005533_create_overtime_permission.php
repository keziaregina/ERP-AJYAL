<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
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
            ['name' => 'essentials.add_overtime_hour'],
            ['name' => 'essentials.edit_overtime_hour'],
        ];

        foreach ($data as $d) {
            Permission::create($d);
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
            ['name' => 'essentials.add_overtime_hour'],
            ['name' => 'essentials.edit_overtime_hour'],
        ];

        foreach ($data as $d) {
            Permission::where('name', $d['name'])->delete();
        }
    }
};
