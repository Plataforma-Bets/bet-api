<?php

// Modelo: app/Models/Jogador.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jogador extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome', 'posicao', 'idade', 'time_id'
    ];

    public function time()
    {
        return $this->belongsTo(Time::class);
    }
}
