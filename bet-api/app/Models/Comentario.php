<?php

// Modelo: app/Models/Comentario.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    use HasFactory;

    protected $fillable = [
        'conteudo', 'tipo', 'time_id', 'user_id'
    ];

    public function time()
    {
        return $this->belongsTo(Time::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}