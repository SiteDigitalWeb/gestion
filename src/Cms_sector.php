<?php

namespace Sitedigitalweb\Gestion;

use Illuminate\Database\Eloquent\Model;

class Cms_sector extends Model

{
 protected $table = 'cms_sector';
 public $timestamps = true;
 protected $fillable = [
        'sectores',
    ];
}
