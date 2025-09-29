<?php

namespace Sitedigitalweb\Gestion;

use Illuminate\Database\Eloquent\Model;

class Cms_funel extends Model
{
    protected $table = 'cms_funel'; // confirma el nombre real de la tabla
    public $timestamps = true;

    public function usuarios()
    {
        return $this->hasMany(Cms_gestion::class, 'funel_id');
    }

     protected $fillable = [
        'funel',
        'color',
    ];
}
