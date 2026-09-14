<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class HealthCheckController
{
    public function __invoke(): Response
    {
        return response('ok', 200)->header('Content-Type', 'text/plain');
    }
}
