<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class EnrollmentController extends ApiController
{
    public function store(Course $course, Request $request)
    {
        $externalUserId = (string) $request->attributes->get('external_user_id');
        $client = (string) $request->attributes->get('external_client');

        $enrollment = DB::transaction(function () use ($externalUserId, $client, $course) {
            return Enrollment::firstOrCreate(
                ['external_user_id' => $externalUserId, 'client' => $client, 'course_id' => $course->id],
                [
                    'status' => 'active',
                    'enrollment_date' => now(),
                    'progress' => 0,
                    'progress_percentage' => 0,
                    'last_activity_at' => now(),
                ]
            );
        });

        return $this->successResponse($enrollment, 'Enrollment completed successfully', status: 201);
    }

    public function userEnrollments(string $externalUserId, Request $request)
    {
        $client = (string) $request->attributes->get('external_client');
        if ($externalUserId !== (string) $request->attributes->get('external_user_id')) {
            return $this->errorResponse('External user mismatch', status: 403);
        }

        $enrollments = Enrollment::query()
            ->where('external_user_id', $externalUserId)
            ->where('client', $client)
            ->with('course')
            ->paginate((int) request()->integer('per_page', 15));

        return $this->paginatedResponse($enrollments, $enrollments->items());
    }

    public function show(Enrollment $enrollment, Request $request)
    {
        $client = (string) $request->attributes->get('external_client');
        $externalUserId = (string) $request->attributes->get('external_user_id');

        if ($enrollment->external_user_id !== $externalUserId || $enrollment->client !== $client) {
            return $this->errorResponse('Forbidden', status: 403);
        }

        return $this->successResponse($enrollment->load('course'));
    }
}
