<?php

namespace Sitedigitalweb\Gestion\Tenant;
use Hyn\Tenancy\Traits\UsesTenantConnection;

use Illuminate\Database\Eloquent\Model;

class Cms_referido extends Model{
 use UsesTenantConnection;
 protected $table = 'cms_referidos';
 public $timestamps = true;
 protected $fillable = [
        'referidos',
    ];
}
