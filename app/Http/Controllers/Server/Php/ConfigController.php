<?php

namespace App\Http\Controllers\Server\Php;

use App\Http\Controllers\Controller;
use App\Models\Server;
use App\Services\PhpService;
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
        $php = new PhpService($server);

        return new JsonResource([
            'config' => $php->getConfig()
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

        $php = new PhpService($server);

        $oldConfig = $php->getConfig();

        $php->setConfig($data['config']);

        $result = $php->checkConfig();

        if ($result !== true) {
            $php->setConfig($oldConfig);
            abort(500, "PHP check failed:\n$result");
        }

        return new JsonResource([
            'config' => $php->getConfig()
        ]);
    }
}
