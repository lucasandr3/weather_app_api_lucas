<?php

namespace App\Service;

use Illuminate\Http\Request;

class QueryStringService
{
    public function preparaQueryString(Request $request): string
    {
        return $request->get('cidade');
    }
}
