<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    /**
     * The primarykey associated with the model.
     *
     * @var string
     */
    protected $primaryKey = 'doc_id';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'doc_documento';

    

    protected $fillable = ['doc_nombre,doc_codigo,doc_path,doc_id_tipo,doc_id_proceso','created_at','updated_at'];
}
