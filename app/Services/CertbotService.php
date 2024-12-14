<?php

namespace App\Services;

use App\Models\Server;
use Illuminate\Support\Str;

class CertbotService
{
    protected const string BASE_PATH = '/etc/letsencrypt';

    public function __construct(
        protected Server $server
    )
    {
    }

    public function getLiveCerts(): array
    {
        $disk = $this->server->disk();

        $path = ltrim(self::BASE_PATH, '/') . '/live/';

        return collect($disk->directories($path))
            ->map(fn($directory) => ['name' => idn_to_utf8(Str::chopStart($directory, $path))])
            ->sortBy('name')
            ->all();
    }

    public function hasLiveCert(string $name): bool
    {
        $disk = $this->server->disk();

        return $disk->exists(ltrim(self::BASE_PATH, '/') . "/live/$name");
    }

    public function requestCertStandalone(string $name): bool|string
    {
        $user = request()->user();

        $domain = collect(explode(",", $name))->map(fn(string $cert) => trim($cert))->join(",");

        $process = $this->server->executeSsh([
            "certbot certonly --standalone --non-interactive --agree-tos --email {$user->email} --domain $domain"
        ]);

        if (!$process->isSuccessful())
            return $process->getErrorOutput();

        return true;
    }
}
