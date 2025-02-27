<?php

namespace App\Listeners;

use App\Events\HistoricoConsulta;
use App\Models\WeatherQueries;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;

class SalvarHistoricoConsulta
{
    public function handle(HistoricoConsulta $event): void
    {
        $usuario = Auth::user()?->getAuthIdentifier();

        WeatherQueries::query()->create([
            'user_id' => $usuario,
            'cidade' => $event->cidade,
            'response' => $event->response
        ]);
    }
}
