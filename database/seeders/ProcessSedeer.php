<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProcessSedeer extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pro_proceso')->insert([
            'pro_prefijo'=> 'ING',
            'pro_nombre' => 'Ingenieria',
        ]);
        //
        DB::table('pro_proceso')->insert([
            'pro_prefijo'=> 'MED',
            'pro_nombre' => 'Medicina',
        ]);
        //
        DB::table('pro_proceso')->insert([
            'pro_prefijo'=> 'CIP',
            'pro_nombre' => 'Ciencias Politicas',
        ]);
        //
        DB::table('pro_proceso')->insert([
            'pro_prefijo'=> 'CON',
            'pro_nombre' => 'Contabilidad',
        ]);
        //
        DB::table('pro_proceso')->insert([
            'pro_prefijo'=> 'ARQ',
            'pro_nombre' => 'Arquitectura',
        ]);
    }
}
