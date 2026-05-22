<?php

namespace App\Services\Api\V1\Identity;

use App\Models\ExternalUser;
use Illuminate\Http\Request;
use InvalidArgumentException;

class ExternalUserResolver
{
    public function resolveFromRequest(Request $request): ExternalUser
    {
        $externalUserId = trim((string) $request->header('X-EXTERNAL-USER-ID', ''));
        $client = trim((string) $request->header('X-CLIENT', ''));

        if ($externalUserId === '' || $client === '') {
            throw new InvalidArgumentException('Missing X-EXTERNAL-USER-ID or X-CLIENT header');
        }

        return ExternalUser::query()->firstOrCreate([
            'external_user_id' => $externalUserId,
            'client' => strtolower($client),
        ]);
    }
}
