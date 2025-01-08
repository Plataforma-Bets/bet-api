<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classificacoes extends Model
{
    use HasFactory;

    // Defina explicitamente o nome da tabela
    protected $table = 'classificacoes'; // Corrija o nome da tabela aqui

    protected $fillable = [
        'campeonato_id', 'time_id', 'posicao', 'jogos', 'vitorias', 'empates', 'derrotas', 'pontos'
    ];

    public function campeonato()
    {
        return $this->belongsTo(Campeonato::class);
    }

    public function time()
    {
        return $this->belongsTo(Time::class);
    }
}