<?php

namespace App\Services;

use App\Enums\PhpExtensionStatus;
use App\Jobs\InstallPhpExtensionJob;
use App\Jobs\UninstallPhpExtensionJob;
use App\Models\PhpExtension;
use App\Models\Server;
use Exception;
use Illuminate\Database\Eloquent\Builder;

class PhpService
{
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
        $disk = $this->server->disk();

        if (!$disk->exists(config('programs.php.cli_path') . '/php.ini')) {
            throw new Exception('PHP CLI config not found.');
        }

        return $disk->get(config('programs.php.cli_path') . '/php.ini');
    }

    public function setConfig(string $data): bool
    {
        $disk = $this->server->disk();

        return $disk->put(config('programs.php.cli_path') . '/php.ini', $data);
    }

    public function checkConfig(): bool|string
    {
        $process = $this->server->executeSsh(['php --ini']);

        if ($process->isSuccessful())
            return true;

        return $process->getErrorOutput();
    }

    public function getExtensions(): array
    {
        $data = config('programs.php.extensions');

        return PhpExtension::query()
                           ->whereHas('program', fn(Builder $has) => $has
                            ->where('server_id', $this->server->id)
                        )
                           ->get()
                           ->filter(fn(PhpExtension $module) => isset($data[$module->name]))
                           ->map(fn(PhpExtension $module) => [
                            'name'   => $module->name,
                            'status' => $module->status->value,
                            ...$data[$module->name]
                        ])
                        ->push(...collect($data)->map(fn(array $values, string $name) => [
                            'name'   => $name,
                            'status' => PhpExtensionStatus::NotInstalled->value,
                            ...$values
                        ]))
                        ->unique('name')
                        ->sortBy('name')
                        ->all();
    }

    public function installExtension(string $name): ?PhpExtension
    {
        $program = $this->server->programs()->where('name', 'php')->first();

        if (!$program)
            return null;

        $extension = PhpExtension::firstOrCreate(['name' => $name, 'program_id' => $program->id]);

        if (!$extension->wasRecentlyCreated && $extension->status != PhpExtensionStatus::NotInstalled)
            return null;

        $extension->update(['status' => PhpExtensionStatus::Installing]);

        InstallPhpExtensionJob::dispatch($extension);

        return $extension;
    }

    public function uninstallExtension(string $name): ?PhpExtension
    {
        $program = $this->server->programs()->where('name', 'php')->first();

        if (!$program)
            return null;

        $extension = PhpExtension::where('name', $name)
                                 ->where('program_id', $program->id)
                                 ->first();

        if ($extension->status != PhpExtensionStatus::Installed)
            return null;

        $extension->update(['status' => PhpExtensionStatus::Uninstalling]);

        UninstallPhpExtensionJob::dispatch($extension);

        return $extension;
    }
}
