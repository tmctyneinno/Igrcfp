<?php

namespace App\DTOs\Api\V1;

class ApiContextData
{
    public function __construct(
        public readonly ?int $actorId,
        public readonly ?int $tokenId,
        public readonly ?string $platform,
    ) {
    }

    public static function fromRequest(\Illuminate\Http\Request $request): self
    {
        $token = $request->user()?->currentAccessToken();

        return new self(
            actorId: $request->user()?->getAuthIdentifier(),
            tokenId: $token?->id,
            platform: $token?->name,
        );
    }
}
