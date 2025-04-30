<?php

use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            ['name' => 'essentials.show_company_bank_page'],
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
            ['name' => 'essentials.show_company_bank_page'],
        ];

        foreach ($data as $d) {
            Permission::where('name', $d['name'])->delete();
        }
    }
};
