<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

class QuizAttemptController extends ApiController
{
    public function start(int|string $quiz, Request $request)
    {
        return $this->successResponse([
            'quiz_id' => $quiz,
            'external_user_id' => (string) $request->attributes->get('external_user_id'),
            'client' => (string) $request->attributes->get('external_client'),
            'state' => 'started',
        ], 'Quiz attempt started');
    }

    public function submit(int|string $quiz, Request $request)
    {
        return $this->successResponse([
            'quiz_id' => $quiz,
            'external_user_id' => (string) $request->attributes->get('external_user_id'),
            'client' => (string) $request->attributes->get('external_client'),
            'submitted' => true,
            'answers_count' => count((array) $request->input('answers', [])),
        ], 'Quiz submitted successfully');
    }

    public function attempts(int|string $quiz, Request $request)
    {
        return $this->successResponse([
            'quiz_id' => $quiz,
            'external_user_id' => (string) $request->attributes->get('external_user_id'),
            'client' => (string) $request->attributes->get('external_client'),
            'items' => [],
        ]);
    }

    public function results(int|string $quiz, Request $request)
    {
        return $this->successResponse([
            'quiz_id' => $quiz,
            'external_user_id' => (string) $request->attributes->get('external_user_id'),
            'client' => (string) $request->attributes->get('external_client'),
            'items' => [],
        ]);
    }
}
