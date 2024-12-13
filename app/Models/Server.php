<?php

namespace App\Models;

use App\Contracts\OperationSystem;
use App\Enums\ServerStatus;
use App\Models\Scopes\UserScope;
use App\OperationSystems\Ubuntu;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
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

    public function sshKey(): BelongsTo
    {
        return $this->belongsTo(SshKey::class);
    }

    public function os(): OperationSystem
    {
        return new Ubuntu($this);
    }

    public function disk(): Filesystem
    {
        return Storage::createSftpDriver([
            "host"       => $this->host,
            "port"       => $this->port,
            "username"   => $this->username,
            "privateKey" => $this->sshKey->private_key,
            "root"       => "/"
        ]);
    }

    public function usePrivateKey(callable $handle)
    {
        $tempKeyPath = sys_get_temp_dir() . '/ssh_temp_key_' . uniqid();
        $privateKey = $this->sshKey->private_key;

        if (!Str::endsWith($privateKey, "\n"))
            $privateKey .= "\n";

        file_put_contents($tempKeyPath, $privateKey);
        chmod($tempKeyPath, 0600);

        $result = $handle($tempKeyPath);

        if (file_exists($tempKeyPath))
            unlink($tempKeyPath);

        return $result;
    }

    public function executeSsh(array $commands): Process
    {
        return $this->usePrivateKey(function ($privateKey) use ($commands) {
            return Ssh::create($this->username, $this->host, $this->port)
                      ->usePrivateKey($privateKey)
                      ->disableStrictHostKeyChecking()
                      ->execute($commands);
        });
    }

    public function check(): bool|string
    {
        $process = $this->executeSsh(['uptime']);

        if ($process->isSuccessful())
            return true;

        return $process->getErrorOutput();
    }
}
