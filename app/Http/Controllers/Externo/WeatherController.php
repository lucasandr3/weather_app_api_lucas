<?php

namespace App\Http\Controllers\Externo;

use App\Http\Controllers\Controller;
use App\Http\Resources\DadosMeteorologicosResource;
use App\Http\Resources\DetalheHistorico;
use App\Http\Resources\HistoricoConsultaResource;
use App\Http\Resources\PaginacaoResource;
use App\Models\WeatherQueries;
use App\Service\OpenWeatherMapService;
use App\Service\QueryStringService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class WeatherController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/weather",
     *     summary="Busca dados meteorológicos de uma cidade",
     *     tags={"Dados Meteorológicos"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\Parameter(
     *         required=true,
     *         name="cidade",
     *         in="query",
     *         description="Nome da cidade que deseja ver os dados meteorológicos",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Header(
     *         header="Accept",
     *         description="Tipo de resposta esperada",
     *         @OA\Schema(type="string", default="application/json")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sucesso",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Requisição não pode ser processada."
     *     )
     * )
     */
    public function buscaDadosMeteorologicos(Request $request): JsonResponse
    {
        $cidade = new QueryStringService()->preparaQueryString($request);
        $dadosMeteorologicos = new OpenWeatherMapService()->getWeather($cidade);

        if ($dadosMeteorologicos->get('status') !== 200) {
            return response()->json($dadosMeteorologicos, 500);
        }

        return response()->json(new DadosMeteorologicosResource($dadosMeteorologicos));
    }

    /**
     * @OA\Get(
     *     path="/api/weather/historico",
     *     summary="Busca histórico de buscas meteorológicas de uma cidade",
     *     tags={"Dados Meteorológicos"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\Parameter(
     *         required=false,
     *         name="usuario",
     *         in="query",
     *         description="Para filtrar por usuário",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *          required=false,
     *          name="periodo",
     *          in="query",
     *          description="Para filtrar pela últimas 24 horas",
     *          @OA\Schema(type="string")
     *      ),
     *     @OA\Header(
     *         header="Accept",
     *         description="Tipo de resposta esperada",
     *         @OA\Schema(type="string", default="application/json")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sucesso",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Requisição não pode ser processada."
     *     )
     * )
     */
    public function buscaHistorico(Request $request): JsonResponse
    {
        $historico = WeatherQueries::query()->with('user');

        if ($request->has('usuario')) {
            $historico->usuario($request->get('usuario'));
        }

        if ($request->has('periodo')) {
            $historico->ultimas24Horas();
        }

        if ($request->has('inicio') && $request->has('fim')) {
            $historico->intervaloData([$request->get('inicio'), $request->get('fim')]);
        }

        return response()->json(HistoricoConsultaResource::collection($historico->get()));
    }

    /**
     * @OA\Get(
     *     path="/api/weather/historico/{codigoHistorico}",
     *     summary="Busca detalhes de um histórico",
     *     tags={"Dados Meteorológicos"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\Header(
     *         header="Accept",
     *         description="Tipo de resposta esperada",
     *         @OA\Schema(type="string", default="application/json")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sucesso",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Requisição não pode ser processada."
     *     )
     * )
     */
    public function detalhesHistorico(int $codigoHistorico): JsonResponse
    {
        $historico = WeatherQueries::query()->with('user')->findOrFail($codigoHistorico);
        return response()->json(new DetalheHistorico($historico));
    }
}
