<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('doc_documento')->insert([
            'doc_nombre'=>'Prueba',     
            'doc_codigo'=>'CON-ARQ-2',     
            'doc_path'=>'preuba',  
            'doc_id_tipo'=>1,    
            'doc_id_proceso'=>1,
        ]);

        /* DB::table('doc_documento')->insert([
            'doc_nombre'=>'Prueba',     
            'doc_codigo'=>'INS-ING-2',     
            'doc_path'=>'preuba',  
            'doc_id_tipo'=>1,    
            'doc_id_proceso'=>1,
        ]);

        DB::table('doc_documento')->insert([
            'doc_nombre'=>'Prueba',     
            'doc_codigo'=>'INS-ING-3',     
            'doc_path'=>'preuba',  
            'doc_id_tipo'=>1,    
            'doc_id_proceso'=>1,
        ]); */
    }
}
