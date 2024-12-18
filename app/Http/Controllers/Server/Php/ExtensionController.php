<?php

namespace App\Http\Controllers\Server\Php;

use App\Enums\PhpExtensionStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\PhpExtensionResource;
use App\Models\PhpExtension;
use App\Models\Server;
use App\Services\PhpService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExtensionController extends Controller
{
    public function index(Server $server)
    {
        $php = new PhpService($server);

        return PhpExtensionResource::collection($php->getExtensions());
    }

    public function store(Request $request, Server $server)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::in(array_keys(config('programs.php.extensions')))],
        ]);

        $php = new PhpService($server);

        if (!($extension = $php->installExtension($data['name'])))
            abort(500, "PHP extension can't be installed.");

        return new PhpExtensionResource($extension->transform());
    }

    public function destroy(Server $server, string $extension)
    {
        $php = new PhpService($server);

        if (!($model = $php->uninstallExtension($extension)))
            abort(500, "PHP extension can't be uninstalled.");

        return new PhpExtensionResource($model->transform());
    }
}
