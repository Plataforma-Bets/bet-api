<?php

// Modelo: app/Models/Campeonato.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campeonato extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome', 'temporada'
    ];

    public function times()
    {
        return $this->belongsToMany(Time::class);
    }
}