<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'liga',
        'escudo',
        'estadio',   
        'tecnico',
    ];

    public function campeonatos()
    {
        return $this->belongsToMany(Campeonato::class);
    }

    public function jogadores()
    {
        return $this->hasMany(Jogador::class);
    }

    public function comentarios()
    {
        return $this->hasMany(Comentario::class);
    }

    public function partidasMandante()
    {
        return $this->hasMany(Partida::class, 'time_mandante_id');
    }

    public function partidasVisitante()
    {
        return $this->hasMany(Partida::class, 'time_visitante_id');
    }
}