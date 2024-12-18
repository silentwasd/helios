<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PhpExtensionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name'        => $this->resource['name'],
            'status'      => $this->resource['status'],
            'label'       => $this->resource['label'],
            'description' => $this->resource['description']
        ];
    }
}
