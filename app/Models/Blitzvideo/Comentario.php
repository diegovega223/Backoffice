<?php

namespace App\Models\Blitzvideo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comentario extends Model
{
    use SoftDeletes;

    protected $connection = 'blitzvideo';
    
    protected $fillable = [
        'usuario_id',
        'video_id',
        'respuesta_id',
        'mensaje',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function video()
    {
        return $this->belongsTo(Video::class, 'video_id');
    }

    public function respuesta()
    {
        return $this->belongsTo(Comentario::class, 'respuesta_id');
    }
}
