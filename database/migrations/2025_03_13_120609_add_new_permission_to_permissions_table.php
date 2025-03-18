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
        // Schema::table('permissions', function (Blueprint $table) {
        //     //
        // });
        Permission::create([
            'guard_name' => 'web',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'name' => 'purchase.create_only',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::where('name', 'purchase.create_only')->delete();
    }
};
