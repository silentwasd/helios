<?php

namespace App\Http\Resources;

use App\Models\SshKey;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin SshKey */
class SshKeyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'private_key' => $this->private_key
        ];
    }
}
