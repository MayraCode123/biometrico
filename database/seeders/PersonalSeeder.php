<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersonalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $personal = [
            [
                'id' => 1, // Specify unique IDs or use auto-increment
                'name' => 'Fernando Flores',
                'area_id' => 4,
                'unidad_id' => 2,
                'data_personal_id' => 4 //estamos llamando al id del biometrico del data
            ],
            [
                'id' => 2, // Specify unique IDs or use auto-increment
                'name' => 'Maria jose',
                'area_id' => 3,
                'unidad_id' => 2,
                'data_personal_id' => 5

            ],
        ];
        DB::table('personal')->insert($personal);

    }
}