<?php

namespace App\Services;

use App\Jobs\RestartProgramServiceJob;
use App\Models\Program;
use App\Models\Server;
use Exception;
use Illuminate\Support\Str;

class NginxService
{
    protected const string BASE_PATH = '/etc/nginx';

    public function __construct(
        protected Server $server
    )
    {
    }

    public function restart(): void
    {
        RestartProgramServiceJob::dispatch(
            $this->server->programs->first(fn(Program $program) => $program->name == 'nginx')
        );
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
        $disk = $this->server->disk();

        $available = $disk->files("etc/nginx/sites-available");
        $enabled   = $disk->files("etc/nginx/sites-enabled");

        $sites = collect($available)
            ->map(fn($path) => basename($path))
            ->map(
                fn($site) => [
                    "name"       => $site,
                    "content"    => "",
                    "is_enabled" => collect($enabled)
                        ->map(fn($path) => basename($path))
                        ->contains($site)
                ]
            );

        return $sites->all();
    }

    public function isSiteEnabled(string $name): bool
    {
        $disk = $this->server->disk();

        return $disk->exists("etc/nginx/sites-enabled/$name");
    }

    public function hasSite(string $name): bool
    {
        $disk = $this->server->disk();

        return $disk->exists("etc/nginx/sites-available/$name");
    }

    public function createSite(string $name, string $content): bool
    {
        $disk = $this->server->disk();

        return $disk->put("etc/nginx/sites-available/$name", $content);
    }

    public function getSite(string $name): array
    {
        $disk = $this->server->disk();

        return [
            "name"       => $name,
            "content"    => $disk->get("etc/nginx/sites-available/$name"),
            "is_enabled" => $this->isSiteEnabled($name)
        ];
    }

    public function updateSite(string $name, string $newName, string $content): bool
    {
        $disk = $this->server->disk();

        if ($name != $newName)
            $disk->move("etc/nginx/sites-available/$name", "etc/nginx/sites-available/$newName");

        return $disk->put("etc/nginx/sites-available/$newName", $content);
    }

    public function enableSite(string $name): bool
    {
        return $this->server->os()
                            ->makeHardLink("/etc/nginx/sites-available/$name", "/etc/nginx/sites-enabled/$name");
    }

    public function disableSite(string $name): bool
    {
        $disk = $this->server->disk();

        return $disk->delete("etc/nginx/sites-enabled/$name");
    }

    public function deleteSite(string $name): string
    {
        $disk = $this->server->disk();

        $disk->delete("etc/nginx/sites-enabled/$name");

        return $disk->delete("etc/nginx/sites-available/$name");
    }

    public function checkSite(): bool|string
    {
        $process = $this->server->executeSsh([
            'nginx -t'
        ]);

        if (!$process->isSuccessful()) {
            return $process->getErrorOutput();
        }

        return true;
    }

    public function getLogs(): array
    {
        $disk = $this->server->disk();

        return collect($disk->allFiles("var/log/nginx"))
            ->map(fn(string $file) => [
                "name"    => Str::chopStart($file, "var/log/nginx/"),
                "content" => ""
            ])
            ->all();
    }

    public function getLog(string $name): array
    {
        $disk = $this->server->disk();

        return [
            'name'    => $name,
            'content' => $disk->get("var/log/nginx/$name")
        ];
    }

    public function clearLog(string $name): bool
    {
        $disk = $this->server->disk();

        return $disk->put("var/log/nginx/$name", "");
    }
}
