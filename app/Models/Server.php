<?php

namespace App\Models;

use App\Enums\ServerStatus;
use App\Models\Scopes\UserScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Ssh\Ssh;

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

    public function check(): bool|string
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
                      ->execute(['uptime']);

        unlink($tempKeyPath);

        if ($process->isSuccessful())
            return true;

        return $process->getErrorOutput();
    }
}
