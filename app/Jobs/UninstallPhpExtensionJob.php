<?php

namespace App\Jobs;

use App\Enums\PhpExtensionStatus;
use App\Models\PhpExtension;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UninstallPhpExtensionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly PhpExtension $extension)
    {
    }

    public function handle(): void
    {
        if ($this->extension->status != PhpExtensionStatus::Uninstalling)
            return;

        if (!$this->extension->program->server->os()->uninstallPackage($this->extension->transform()['package'])) {
            $this->extension->update(['status' => PhpExtensionStatus::Installed]);
            return;
        }

        $this->extension->update(['status' => PhpExtensionStatus::NotInstalled]);
    }
}
