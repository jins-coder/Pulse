<?php

declare(strict_types=1);

namespace Pulse\Studio;

use Pulse\Pulse;
use Pulse\Http\Request;
use Pulse\Http\Response;

class Studio
{
    public static function handle(Request $request): Response
    {
        $html = view('studio', [
            'title' => 'Pulse Studio • Developer Cockpit (v2.0)',
            'version' => Pulse::VERSION,
            'codename' => Pulse::CODENAME,
            'timestamp' => time(),
        ]);

        return Response::html($html);
    }
}
