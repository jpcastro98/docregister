<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        DB::table('tip_tipo_doc')->insert([
            'tip_prefijo'=> 'INS',
            'tip_nombre' => 'Instructivo',
        ]);
        //
        DB::table('tip_tipo_doc')->insert([
            'tip_prefijo'=> 'INF',
            'tip_nombre' => 'Informe',
        ]);
        //
        DB::table('tip_tipo_doc')->insert([
            'tip_prefijo'=> 'CON',
            'tip_nombre' => 'Contrato',
        ]);
        //
        DB::table('tip_tipo_doc')->insert([
            'tip_prefijo'=> 'POL',
            'tip_nombre' => 'Politica',
        ]);
        //
        DB::table('tip_tipo_doc')->insert([
            'tip_prefijo'=> 'PDP',
            'tip_nombre' => 'Plan de proyecto',
        ]);
    
    }
}
