<?php

namespace Sitedigitalweb\Gestion;

use Illuminate\Database\Eloquent\Model;

class Cms_product extends Model

{
 protected $table = 'cms_products';
 public $timestamps = true; 

 protected $fillable = [
        'iva',
        'identificador',
        'posti',
        'precio',
        'producto',
        'descripcion',
        'propuesta_id',
        'moneda',
        'valor_subtotal',
        'valor_iva',
        'valor_total',
    ];

}
