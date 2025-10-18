<?php

namespace Sitedigitalweb\Gestion\Tenant;
use Hyn\Tenancy\Traits\UsesTenantConnection;

use Illuminate\Database\Eloquent\Model;

class Cms_sector extends Model{
 use UsesTenantConnection;
 protected $table = 'cms_sector';
 public $timestamps = true;
 protected $fillable = [
        'sectores',
    ];
}

