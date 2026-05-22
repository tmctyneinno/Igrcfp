<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BaseApiCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'items' => $this->collection,
        ];
    }

    public function with(Request $request): array
    {
        $pagination = method_exists($this->resource, 'total')
            ? [
                'current_page' => $this->resource->currentPage(),
                'per_page' => $this->resource->perPage(),
                'total' => $this->resource->total(),
                'last_page' => $this->resource->lastPage(),
                'from' => $this->resource->firstItem(),
                'to' => $this->resource->lastItem(),
            ]
            : null;

        return [
            'meta' => [
                'pagination' => $pagination,
            ],
        ];
    }
}
