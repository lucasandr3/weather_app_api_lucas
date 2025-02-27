<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class HistoricoErroConsulta
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $cidade;
    public array $response;

    public function __construct($cidade, $response)
    {
        $this->cidade = $cidade;
        $this->response = $response;
    }
}
