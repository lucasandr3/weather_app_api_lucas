<?php

namespace App\Service;

use App\Events\HistoricoConsulta;
use App\Events\HistoricoErroConsulta;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Fluent;

readonly class OpenWeatherMapService
{
    private const string API_URL = 'https://api.openweathermap.org/data/2.5/weather?';

    public function getWeather($cidade): Fluent
    {
        $queryString = $this->preparaParametros($cidade);
        $response = Http::get(self::API_URL, $queryString);

        if ($response->ok() === false) {
            event(new HistoricoErroConsulta($cidade, $response->json()));
            return new Fluent([
                'erro' => true,
                'message' => 'Algo deu errado, tente novamente.',
                'status' => $response->status(),
                'response' => []]
            );
        }

        event(new HistoricoConsulta($cidade, $response->json()));
        return new Fluent([
            'erro' => false,
            'message' => '',
            'status' => $response->status(),
            'response' => $response->object()]
        );
    }

    private function preparaParametros(string $cidade): string
    {
        return http_build_query([
            'q' => $cidade,
            'appid' => env('OPENWEATHER_API_KEY'),
            'units' => 'metric',
            'lang' => 'pt_br'
        ]);
    }
}
