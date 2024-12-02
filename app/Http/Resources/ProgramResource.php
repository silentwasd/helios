<?php

namespace App\Http\Resources;

use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Program */
class ProgramResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->resource['id'],
            'name'        => $this->resource['name'],
            'label'       => $this->resource['label'],
            'description' => $this->resource['description'],
            'status'      => $this->resource['status'],
            'has_service' => $this->resource['has_service']
        ];
    }
}
