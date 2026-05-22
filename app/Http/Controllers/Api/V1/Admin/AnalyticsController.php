<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;

class AnalyticsController extends ApiController
{
    public function index()
    {
        return $this->successResponse(['enrollments' => 0, 'active_learners' => 0]);
    }

    public function courseStatistics()
    {
        return $this->successResponse(['items' => []]);
    }

    public function engagementMetrics()
    {
        return $this->successResponse(['items' => []]);
    }

    public function dropoffAnalysis()
    {
        return $this->successResponse(['items' => []]);
    }
}
