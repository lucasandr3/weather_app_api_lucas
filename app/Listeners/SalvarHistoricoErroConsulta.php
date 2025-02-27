<?php

namespace App\Listeners;

use App\Events\HistoricoErroConsulta;
use App\Models\WeatherFails;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;

class SalvarHistoricoErroConsulta
{
    public function handle(HistoricoErroConsulta $event): void
    {
        $usuario = Auth::user()?->getAuthIdentifier();

        WeatherFails::query()->create([
            'user_id' => $usuario,
            'cidade' => $event->cidade,
            'response' => $event->response
        ]);
    }
}
