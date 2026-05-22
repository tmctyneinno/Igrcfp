<?php

namespace App\Http\Controllers\Api\V1;

class CertificateController extends ApiController
{
    public function index()
    {
        return $this->successResponse(['items' => []]);
    }

    public function show(int|string $certificate)
    {
        return $this->successResponse(['id' => $certificate]);
    }

    public function verify(string $code)
    {
        return $this->successResponse(['verification_code' => $code, 'is_valid' => false]);
    }
}
