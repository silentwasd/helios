<?php

namespace App\Http\Controllers\Server\Nginx;

use App\Http\Controllers\Controller;
use App\Models\Server;
use App\Services\NginxService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConfigController extends Controller
{
    public function show(Server $server)
    {
        $nginx = new NginxService($server);

        return new JsonResource([
            'config' => $nginx->getConfig()
        ]);
    }

    public function update(Request $request, Server $server)
    {
        $data = $request->validate([
            'config' => 'required|string'
        ]);

        $nginx = new NginxService($server);

        $nginx->setConfig($data['config']);

        return new JsonResource([
            'config' => $nginx->getConfig()
        ]);
    }
}
