<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CheckSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('check_games')->insert([
            ['name'=>'Ожидает подтверждения'],
            ['name'=>'Принята'],
            ['name'=>'Отклонена'],
        ]);
    }
}
