<?php
namespace App\Traits;

use Hashids\Hashids;

trait HashableId
{
    protected static function getHasher()
    {
        // Use your APP_KEY as the salt for security
        return new Hashids(config('app.key'), 6); // 6 characters min length
    }

    public function getEncodedIdAttribute()
    {
        return self::getHasher()->encode($this->getKey());
    }

    public static function decodeId($encodedId)
    {
        $decoded = self::getHasher()->decode($encodedId);
        return !empty($decoded) ? $decoded[0] : null;
    }
}