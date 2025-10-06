<?php

namespace Sitedigitalweb\Gestion;

use Illuminate\Database\Eloquent\Model;

class Cms_config extends Model

{
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