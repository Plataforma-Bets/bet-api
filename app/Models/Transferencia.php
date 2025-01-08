<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transferencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'time_id',
        'jogador',
        'tipo', // contratado, emprestado ou vendido
    ];

    /**
     * Relacionamento com o modelo Time.
     */
    public function time()
    {
        return $this->belongsTo(Time::class);
    }
}