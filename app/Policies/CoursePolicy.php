<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    public function viewAny(?User $user = null): bool
    {
        return true;
    }

    public function view(?User $user, Course $course): bool
    {
        return in_array($course->status, ['published', 'active'], true);
    }
}
