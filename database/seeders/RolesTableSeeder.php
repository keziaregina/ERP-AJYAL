<?php

namespace Database\Seeders;

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            ['name' => 'Super Admin', 'guard_name' => 'web', 'business_id' => '1', 'is_default' => '1', 'is_service_staff' => '0' ]
        ]);

        $user = User::where('first_name', 'like', '%NASSER%')->first();
        $user->assignRole('Super Admin');
    }
}
