<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class DetalheHistorico extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'usuario' => $this->user->name,
            'descricao' => ucfirst($this->response['weather'][0]['description']),
            'icone' => $this->response['weather'][0]['icon'],
            'temperatura' => round($this->response['main']['temp']),
            'sensacao_termica' => round($this->response['main']['feels_like']),
            'temp_min' => floor($this->response['main']['temp_min']),
            'temp_max' => round($this->response['main']['temp_max']),
            'humidade' => $this->response['main']['humidity'],
            'vento' => $this->response['wind']['speed'],
            'cidade' => $this->response['name'],
            'pais' => $this->response['sys']['country'],
            'data_consulta' => Carbon::parse($this->created_at)->format('d/m/Y H:i:s'),
            'data_consulta_texto' => Carbon::parse($this->created_at)->diffForHumans()
        ];
    }
}
