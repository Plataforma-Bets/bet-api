<?php

// Modelo: app/Models/Estadio.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estadio extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome', 'localizacao', 'altitude', 'tipo_gramado', 'time_id'
    ];

    public function time()
    {
        return $this->belongsTo(Time::class);
    }
}
