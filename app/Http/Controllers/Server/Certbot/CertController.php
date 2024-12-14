<?php

namespace App\Http\Controllers\Server\Certbot;

use App\Http\Controllers\Controller;
use App\Http\Resources\CertbotCertResource;
use App\Models\Server;
use App\Services\CertbotService;
use Illuminate\Http\Request;

class CertController extends Controller
{
    public function index(Server $server)
    {
        $certbot = new CertbotService($server);

        return CertbotCertResource::collection($certbot->getLiveCerts());
    }

    public function store(Request $request, Server $server)
    {
        $certbot = new CertbotService($server);

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                function ($attribute, $value, $fail) use ($certbot) {
                    $exist = collect(explode(",", $value))
                        ->map(fn(string $cert) => trim($cert))
                        ->map(fn(string $cert) => idn_to_ascii($cert))
                        ->filter(fn(string $cert) => $certbot->hasLiveCert($cert))
                        ->map(fn(string $cert) => idn_to_utf8($cert))
                        ->join(", ");

                    if ($exist)
                        $fail("The {$attribute} field has already exist values: {$exist}.");
                }
            ],
        ]);

        if (($result = $certbot->requestCertStandalone($data['name'])) && $result !== true)
            abort(500, $result);
    }

    public function update(Server $server, string $name)
    {
        $certbot = new CertbotService($server);

        if (!$certbot->hasLiveCert($name))
            abort(404, "Cert not found.");

        if (($result = $certbot->renewCert($name)) && $result !== true)
            abort(500, $result);
    }
}
