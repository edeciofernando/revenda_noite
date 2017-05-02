<?php

use Illuminate\Database\Seeder;

class MarcasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('marcas')->insert(['nome' => 'Fiat']);
        DB::table('marcas')->insert(['nome' => 'Chevrolet']);
        DB::table('marcas')->insert(['nome' => 'Volkswagen']);
        DB::table('marcas')->insert(['nome' => 'Renault']);
        DB::table('marcas')->insert(['nome' => 'Ford']);                
    }
}
