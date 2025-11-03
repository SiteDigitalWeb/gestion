<?php

namespace Sitedigitalweb\Gestion\Tenant;
use Hyn\Tenancy\Traits\UsesTenantConnection;


use Illuminate\Database\Eloquent\Model;

class Cms_config extends Model

{
  use UsesTenantConnection;
 protected $table = 'cms_configuracion';
 public $timestamps = false;

  protected $fillable = [
        'empresa',
        'direccion',
        'telefono',
        'correo',
        'website',
        'logo',
        'img_01',
        'img_02',
        'presentacion',
        'color_principal',
        'color_secundario',
    ];
}
