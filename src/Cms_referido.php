<?php

namespace Sitedigitalweb\Gestion;

use Illuminate\Database\Eloquent\Model;

class Cms_referido extends Model

{
 protected $table = 'cms_referidos';
 public $timestamps = true;
 protected $fillable = [
        'referidos',
    ];
}
