<?php

namespace App\Http\Resources\Api\V1\Course;

use App\Http\Resources\Api\V1\BaseApiCollection;

class CourseCollection extends BaseApiCollection
{
    public $collects = CourseResource::class;
}
