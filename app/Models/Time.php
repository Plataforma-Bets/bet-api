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
        'escudo'
    ];

    public function estadio()
    {
        return $this->hasOne(Estadio::class);
    }

    public function campeonatos()
    {
        return $this->belongsToMany(Campeonato::class);
    }

    public function jogadores()
    {
        return $this->hasMany(Jogador::class);
    }

    public function tecnico()
    {
        return $this->hasOne(Tecnico::class);
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