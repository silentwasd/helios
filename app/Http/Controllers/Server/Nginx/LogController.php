<?php

namespace App\Http\Controllers\Server\Nginx;

use App\Http\Controllers\Controller;
use App\Http\Resources\NginxLogResource;
use App\Models\Server;
use App\Services\NginxService;

class LogController extends Controller
{
    public function index(Server $server)
    {
        $nginx = new NginxService($server);

        return NginxLogResource::collection($nginx->getLogs());
    }

    public function show(Server $server, string $name)
    {
        $nginx = new NginxService($server);

        return new NginxLogResource($nginx->getLog($name));
    }

    public function destroy(Server $server, string $name)
    {
        $nginx = new NginxService($server);

        if (!$nginx->clearLog($name))
            abort(500, "Failed to clear log.");
    }
}
