<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WeatherQueries;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        $usuarios = User::all()->count();
        $consultas = WeatherQueries::all(['id', 'cidade']);
        $totalConsultas = $consultas->count();
        $totalCidades = $consultas->pluck('cidade')->unique()->count();

        return response()->json(['usuarios'=>$usuarios,'consultas'=> $totalConsultas,'cidades'=>$totalCidades]);
    }
}
