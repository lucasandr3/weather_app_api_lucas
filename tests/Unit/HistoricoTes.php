<?php

use App\Http\Controllers\Externo\WeatherController;
use App\Http\Resources\HistoricoConsultaResource;
use App\Models\WeatherQueries;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use function Pest\Laravel\mock;
use Illuminate\Database\Eloquent\Builder;


it('retorna histórico corretamente', function () {
    $queryMock = Mockery::mock('Illuminate\Database\Eloquent\Builder');

    $queryMock->shouldReceive('with')->with('user')->andReturnSelf();
    $queryMock->shouldReceive('usuario')->andReturnSelf();
    $queryMock->shouldReceive('ultimas24Horas')->andReturnSelf();
    $queryMock->shouldReceive('intervaloData')->andReturnSelf();
    $queryMock->shouldReceive('get')->andReturn(collect([]));

    WeatherQueries::shouldReceive('query')->andReturn($queryMock);

    $request = Request::create('/api/weather/historico', 'GET');

    $controller = new WeatherController();

    $response = $controller->buscaHistorico($request);

    expect($response)->toBeInstanceOf(JsonResponse::class)
        ->and($response->getData())->toBe([]);
});




