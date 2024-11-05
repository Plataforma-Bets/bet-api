<?php

// Modelo: app/Models/Tecnico.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tecnico extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome', 'time_id'
    ];

    public function time()
    {
        return $this->belongsTo(Time::class);
    }
}