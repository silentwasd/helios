<?php

namespace App\Http\Controllers\Server\Nginx;

use App\Http\Controllers\Controller;
use App\Models\Server;
use App\Services\NginxService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConfigController extends Controller
{
    /**
     * @throws Exception
     */
    public function show(Server $server)
    {
        $nginx = new NginxService($server);

        return new JsonResource([
            'config' => $nginx->getConfig()
        ]);
    }

    /**
     * @throws Exception
     */
    public function update(Request $request, Server $server)
    {
        $data = $request->validate([
            'config' => 'required|string'
        ]);

        $nginx = new NginxService($server);

        $oldConfig = $nginx->getConfig();

        $nginx->setConfig($data['config']);

        $result = $nginx->checkSite();

        if ($result !== true) {
            $nginx->setConfig($oldConfig);
            abort(500, "Nginx check failed:\n$result");
        }

        $nginx->restart();

        return new JsonResource([
            'config' => $nginx->getConfig()
        ]);
    }
}
