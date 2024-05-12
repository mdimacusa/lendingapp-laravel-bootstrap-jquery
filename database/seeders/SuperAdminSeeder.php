<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'unique_id'     => rand(11111111,99999999),
            'name'          => "Super Admin",
            'email'         => "superadmin@gmail.com",
            'pincode'       => 1111,
            'password'      => Hash::make("admin123"),
            'role'          => "SUPER ADMINISTRATOR",
            'status'        => "ACTIVE",
        ]);

        // DB::table('client')->insert([
        //     'unique_id'      => rand(11111111,99999999),
        //     'first_name'     => "pioo",
        //     'surname'        => "antipolo",
        //     'email'          => "test@gmail.com",
        //     'contact_number' => "09497941399",
        //     'address'        => "Taguig Metro Manila",
        //     'status'         => "ACTIVE",
        // ]);
    }
}
