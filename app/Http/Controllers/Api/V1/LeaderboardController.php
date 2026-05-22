<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Course;
use App\Models\User;

class LeaderboardController extends ApiController
{
    public function global()
    {
        return $this->successResponse(['items' => []]);
    }

    public function course(Course $course)
    {
        return $this->successResponse(['course_id' => $course->id, 'items' => []]);
    }

    public function userRanking(User $user)
    {
        return $this->successResponse(['user_id' => $user->id, 'ranking' => null]);
    }
}
