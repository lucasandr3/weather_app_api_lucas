<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeatherQueries extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'cidade', 'response'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'response' => 'array'
    ];

    public function scopeUsuario($query, string $nomeUsuario)
    {
        return $query
            ->select('users.name', 'weather_queries.*')
            ->join('users', 'users.id', '=', 'weather_queries.user_id')
            ->where('users.name', 'LIKE', "%{$nomeUsuario}%");
    }

    public function scopeUltimas24Horas($query)
    {
        return $query->where('weather_queries.created_at', '>=', now()->subDay());
    }

    public function scopeIntervaloData($query, $intervalo)
    {
        return $query->whereBetween('weather_queries.created_at', $intervalo);
    }
}
