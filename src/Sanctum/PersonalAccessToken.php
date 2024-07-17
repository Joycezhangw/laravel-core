<?php

namespace LanDao\LaravelCore\Sanctum;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

/**
 * 来源：https://github.com/abrahamgreyson/cache-for-laravel-sanctum
 */
class PersonalAccessToken extends SanctumPersonalAccessToken
{
    /**
     * 缓存令牌秒数
     * @var int
     */
    public static int $ttl = 3600;

    /**
     * 查找与给定令牌匹配的令牌实例
     * @param $token
     * @return static|null
     */
    public static function findToken($token): ?static
    {
        [$id, $token] = !str_contains($token, '|') ? [null, $token] : explode('|', $token, 2);
        $hashedToken = hash('sha256', $token);

        $cachedToken = Cache::remember(
            "personal-access-token:$hashedToken",
            config('sanctum.cache.ttl') ?? self::$ttl,
            function () use ($token) {
                return parent::findToken($token) ?? '_null_';
            }
        );

        if ($cachedToken === '_null_' || ! hash_equals($cachedToken->token, $hashedToken)) {
            return null;
        }

        return $cachedToken;
    }

    public static function boot(): void
    {
        parent::boot();

        static::updating(function (self $personalAccessToken) {
            // update cache last_use_at
        });

        static::deleting(function (self $personalAccessToken) {
            Cache::forget("personal-access-token:{$personalAccessToken->token}");
            Cache::forget("personal-access-token:{$personalAccessToken->token}:tokenable");
        });
    }

    /**
     * @return Attribute
     */
    public function tokenable(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => Cache::remember(
                "personal-access-token:{$attributes['token']}:tokenable",
                config('sanctum.cache.ttl') ?? self::$ttl,
                function () {
                    return parent::tokenable()->first();
                }
            )
        );
    }

}