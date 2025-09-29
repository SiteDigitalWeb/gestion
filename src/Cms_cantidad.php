<?php

namespace Sitedigitalweb\Gestion;

use Illuminate\Database\Eloquent\Model;

class Cms_cantidad extends Model
{
 protected $table = 'cms_cantidad';
 public $timestamps = true;
     protected $fillable = [
        'cantidad'
    ];

}
