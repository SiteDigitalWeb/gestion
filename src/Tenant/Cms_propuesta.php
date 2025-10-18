<?php

namespace Sitedigitalweb\Gestion\Tenant;
use Hyn\Tenancy\Traits\UsesTenantConnection;

use Illuminate\Database\Eloquent\Model;

class Cms_propuesta extends Model{
 
 use UsesTenantConnection;
 protected $table = 'cms_propuestas';
 public $timestamps = true;

 protected $fillable = [
        'estado_propuesta',
        'valor_propuesta',
        'fecha_presentacion',
        'tarifas',
        'identificador',
        'asunto',
        'presentacion',
        'producto_servicio',
        'observaciones',
        'cms_user_id',
        'motivo_id',
    ];
}