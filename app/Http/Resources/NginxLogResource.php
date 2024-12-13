<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NginxLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name'    => $this->resource['name'],
            'content' => $this->resource['content'] ?? ''
        ];
    }
}
