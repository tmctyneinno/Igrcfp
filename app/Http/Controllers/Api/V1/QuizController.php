<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Assessment;

class QuizController extends ApiController
{
    public function show(Assessment $quiz)
    {
        return $this->successResponse($quiz);
    }
}
