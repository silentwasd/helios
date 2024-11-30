<?php

namespace App\Models;

use App\Contracts\OperationSystem;
use App\Enums\ServerStatus;
use App\Models\Scopes\UserScope;
use App\OperationSystems\Ubuntu;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Spatie\Ssh\Ssh;
use Symfony\Component\Process\Process;

#[ScopedBy(UserScope::class)]
class Server extends Model
{
    protected $fillable = [
        'name',
        'host',
        'port',
        'username',
        'ssh_key_id',
        'user_id'
    ];

    protected $casts = [
        'status' => ServerStatus::class
    ];

    public function programs(): HasMany
    {
        return $this->hasMany(Program::class);
    }

    public function os(): OperationSystem
    {
        return new Ubuntu($this);
    }

    public function executeSsh(array $commands): Process
    {
        $tempKeyPath = sys_get_temp_dir() . '/ssh_temp_key_' . uniqid();
        $privateKey = SshKey::findOrFail($this->ssh_key_id)->private_key;

        if (!Str::endsWith($privateKey, "\n"))
            $privateKey .= "\n";

        file_put_contents($tempKeyPath, $privateKey);
        chmod($tempKeyPath, 0600);

        $process = Ssh::create($this->username, $this->host, $this->port)
                      ->usePrivateKey($tempKeyPath)
                      ->disableStrictHostKeyChecking()
                      ->removeBash()
                      ->execute($commands);

        unlink($tempKeyPath);

        return $process;
    }

    public function check(): bool|string
    {
        $process = $this->executeSsh(['uptime']);

        if ($process->isSuccessful())
            return true;

        return $process->getErrorOutput();
    }
}
