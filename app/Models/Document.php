<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;


class Document extends Model
{   
    use HasSlug;
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

    /**
     * The slug class.
     *
     * @var \Spatie\Sluggable\HasSlug;
     */
 

    protected $fillable = ['doc_nombre,doc_codigo,doc_path,doc_id_tipo,doc_id_proceso','created_at','updated_at'];


    function getSlugOptions() : SlugOptions {

        return SlugOptions::create()->generateSlugsFrom('doc_nombre')->saveSlugsTo('slug')->slugsShouldBeNoLongerThan(50)->usingLanguage('es');

    }

    public function getRouteKeyName()
{
    return 'slug';
}


}
