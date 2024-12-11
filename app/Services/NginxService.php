<?php

namespace App\Services;

use App\Models\Server;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class NginxService
{
    protected const string BASE_PATH = '/etc/nginx';

    public function __construct(
        protected Server $server
    )
    {
    }

    /**
     * @throws Exception
     */
    public function getConfig(): string
    {
        $process = $this->server->executeSsh([
            sprintf('cat %s/nginx.conf', self::BASE_PATH)
        ]);

        if (!$process->isSuccessful()) {
            throw new Exception($process->getErrorOutput());
        }

        return $process->getOutput();
    }

    public function setConfig(string $data): bool
    {
        $process = $this->server->executeSsh([
            sprintf('echo "%s" > %s/nginx.conf', addslashes($data), self::BASE_PATH)
        ]);

        return $process->isSuccessful();
    }

    public function getSites(): array
    {
        return $this->server->usePrivateKey(function ($privateKey) {
            return Storage::createSftpDriver([
                'host'       => $this->server->host,
                'port'       => $this->server->port,
                'username'   => $this->server->username,
                'privateKey' => $this->server->sshKey->private_key
            ])->directories('\etc\nginx');
        });
    }
}
