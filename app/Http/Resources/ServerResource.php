<?php

namespace App\Http\Resources;

use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Server */
class ServerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'host'       => $this->host,
            'port'       => $this->port,
            'username'   => $this->username,
            'ssh_key_id' => $this->ssh_key_id,
            'status'     => $this->status
        ];
    }
}
