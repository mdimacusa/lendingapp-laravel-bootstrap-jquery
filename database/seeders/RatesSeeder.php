<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class RatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $query = [
            [
                'rate'  => '0.05',
            ],
            [
                'rate'  => '0.10',
            ],
            [
                'rate'  => '0.15',
            ],
            [
                'rate'  => '0.20',
            ]
        ];

        foreach($query as $data) {
            DB::table('rates')->insert($data);
        }
    }
}
