<?php

namespace Sitedigitalweb\Gestion;

use Illuminate\Database\Eloquent\Model;

class Cms_propuesta extends Model

{
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