<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partida extends Model
{
    use HasFactory;

    protected $fillable = [
        'data',
        'horario',
        'time_mandante_id',
        'time_visitante_id',
        'placar_mandante',
        'placar_visitante',
        'campeonato_id',
        'tipo'
    ];

    public function campeonato()
    {
        return $this->belongsTo(Campeonato::class);
    }

    public function timeMandante()
    {
        return $this->belongsTo(Time::class, 'time_mandante_id');
    }

    public function timeVisitante()
    {
        return $this->belongsTo(Time::class, 'time_visitante_id');
    }
}