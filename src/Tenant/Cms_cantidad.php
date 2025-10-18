<?php

namespace Sitedigitalweb\Gestion\Tenant;
use Hyn\Tenancy\Traits\UsesTenantConnection;

use Illuminate\Database\Eloquent\Model;

class Cms_cantidad extends Model
{
use UsesTenantConnection;
 protected $table = 'cms_cantidad';
 public $timestamps = true;
     protected $fillable = [
        'cantidad'
    ];

}
