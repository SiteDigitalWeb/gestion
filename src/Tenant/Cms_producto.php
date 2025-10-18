<?php

namespace Sitedigitalweb\Gestion\Tenant;
use Hyn\Tenancy\Traits\UsesTenantConnection;

use Illuminate\Database\Eloquent\Model;

class Cms_producto extends Model{

 use UsesTenantConnection; 
 protected $table = 'cms_productos';

 public $timestamps = true;
 protected $fillable = [
        'producto',
        'identificador',
    ];
}

