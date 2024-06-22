<?php

namespace App\Models\Blitzvideo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    use SoftDeletes;

    protected $connection = 'blitzvideo';

    protected $fillable = [
        'titulo',
        'descripcion',
        'link',
        'activo',
        'canal_id',
        'miniatura'
    ];

    public function canal()
    {
        return $this->belongsTo(Canal::class);
    }

    public function etiquetas()
    {
        return $this->belongsToMany(Etiqueta::class);
    }

    public function visitas()
    {
        return $this->hasMany(Visita::class);
    }

    public function puntuaciones()
    {
        return $this->hasMany(Puntua::class);
    }

    public function getPuntuacionPromedioAttribute()
    {
        $promedio = $this->puntuaciones()->avg('valora');
        return round($promedio);
    }
}