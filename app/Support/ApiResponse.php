<?php

namespace App\Support;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    public function successResponse(mixed $data = null, string $message = 'Data fetched successfully', array $meta = [], int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data ?? (object) [],
            'meta' => (object) $meta,
        ], $status);
    }

    public function errorResponse(string $message = 'An unexpected error occurred', mixed $data = null, array $meta = [], int $status = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $data ?? (object) [],
            'meta' => (object) $meta,
        ], $status);
    }

    public function paginatedResponse(LengthAwarePaginator $paginator, mixed $data, string $message = 'Data fetched successfully', array $meta = []): JsonResponse
    {
        return $this->successResponse($data, $message, array_merge([
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
                'has_more_pages' => $paginator->hasMorePages(),
            ],
        ], $meta));
    }
}
