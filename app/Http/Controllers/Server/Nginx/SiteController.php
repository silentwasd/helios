<?php

namespace App\Http\Controllers\Server\Nginx;

use App\Http\Controllers\Controller;
use App\Http\Resources\NginxSiteResource;
use App\Models\Server;
use App\Services\NginxService;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index(Server $server)
    {
        $nginx = new NginxService($server);

        return NginxSiteResource::collection($nginx->getSites());
    }

    public function store(Request $request, Server $server)
    {
        $nginx = new NginxService($server);

        $data = $request->validate([
            'name'    => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($nginx) {
                    if ($nginx->hasSite($value))
                        $fail("The {$attribute} field already exists.");
                }
            ],
            'content' => 'required|string'
        ]);

        if (!$nginx->createSite($data['name'], $data['content']))
            abort(500, "Failed to create site.");
    }

    public function show(Server $server, string $name)
    {
        $nginx = new NginxService($server);

        if (!$nginx->hasSite($name))
            abort(404, "Site not found.");

        return new NginxSiteResource($nginx->getSite($name));
    }

    public function update(Request $request, Server $server, string $name)
    {
        $nginx = new NginxService($server);

        if (!$nginx->hasSite($name))
            abort(404, "Site not found.");

        $data = $request->validate([
            'new_name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($nginx, $name) {
                    if ($name != $value && $nginx->hasSite($value))
                        $fail("The {$attribute} field already exists.");
                }
            ],
            'content'  => 'required|string'
        ]);

        if (!$nginx->updateSite($name, $data['new_name'], $data['content']))
            abort(500, "Failed to update site.");
    }

    public function enable(Server $server, string $name)
    {
        $nginx = new NginxService($server);

        if (!$nginx->hasSite($name))
            abort(404, "Site not found.");

        if (!$nginx->enableSite($name))
            abort(500, "Failed to enable site.");

        $nginx->restart();
    }

    public function disable(Server $server, string $name)
    {
        $nginx = new NginxService($server);

        if (!$nginx->hasSite($name))
            abort(404, "Site not found.");

        if (!$nginx->disableSite($name))
            abort(500, "Failed to disable site.");

        $nginx->restart();
    }

    public function destroy(Server $server, string $name)
    {
        $nginx = new NginxService($server);

        if (!$nginx->hasSite($name))
            abort(500, "Site not exists.");

        if (!$nginx->deleteSite($name))
            abort(500, "Failed to delete site.");
    }
}
