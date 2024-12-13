<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NginxSiteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name'       => $this->resource['name'],
            'content'    => $this->resource['content'] ?? '',
            'is_enabled' => $this->resource['is_enabled']
        ];
    }
}
