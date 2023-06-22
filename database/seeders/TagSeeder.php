<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tags')->insert([
            ['name'=>'драма'],
            ['name'=>'романтика'],
            ['name'=>'детектив'],
            ['name'=>'фантастика'],
            ['name'=>'фэнтези'],
            ['name'=>'триллер'],
            ['name'=>'хоррор'],
            ['name'=>'боевик'],
            ['name'=>'гарем'],
            ['name'=>'комедия'],
            ['name'=>'приключения'],
            ['name'=>'психология'],
            ['name'=>'спорт'],
        ]);
    }
}
