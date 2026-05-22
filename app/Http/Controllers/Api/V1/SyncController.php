<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Enrollment;
use Illuminate\Http\Request;

class SyncController extends ApiController
{
    public function courses()
    {
        return $this->successResponse(['items' => []]);
    }

    public function course(int|string $id)
    {
        return $this->successResponse(['id' => $id]);
    }

    public function progress(string $externalUserId, Request $request)
    {
        if ($externalUserId !== (string) $request->attributes->get('external_user_id')) {
            return $this->errorResponse('External user mismatch', status: 403);
        }

        $client = (string) $request->attributes->get('external_client');
        $items = Enrollment::query()
            ->where('external_user_id', $externalUserId)
            ->where('client', $client)
            ->get(['course_id', 'status', 'progress_percentage', 'last_activity_at']);

        return $this->successResponse(['external_user_id' => $externalUserId, 'items' => $items]);
    }

    public function leaderboards()
    {
        return $this->successResponse(['items' => []]);
    }

    public function certificates(string $externalUserId, Request $request)
    {
        if ($externalUserId !== (string) $request->attributes->get('external_user_id')) {
            return $this->errorResponse('External user mismatch', status: 403);
        }

        return $this->successResponse(['external_user_id' => $externalUserId, 'items' => []]);
    }
}
