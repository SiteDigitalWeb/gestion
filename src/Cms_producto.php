<?php

namespace Sitedigitalweb\Gestion;

use Illuminate\Database\Eloquent\Model;

class Cms_producto extends Model{

 protected $table = 'cms_productos';

 public $timestamps = true;
 protected $fillable = [
        'producto',
        'identificador',
    ];
}
