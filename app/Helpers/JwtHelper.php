<?php

namespace App\Helpers;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use InvalidArgumentException;

class JwtHelper
{
    public static function encode(array $payload, string $secret): string
    {
        return JWT::encode($payload, $secret, 'HS256');
    }

    public static function decode(string $token, string $secret): ?object
    {
        try {
            return JWT::decode($token, new Key($secret, 'HS256'));
        } catch (\Throwable $exception) {
            return null;
        }
    }

    public static function payloadForUser(User $user, int $ttlMinutes = 60): array
    {
        return [
            'sub' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'iat' => now()->timestamp,
            'exp' => now()->addMinutes($ttlMinutes)->timestamp,
        ];
    }

    public static function tokenForUser(User $user, string $secret, int $ttlMinutes = 60): string
    {
        if ($secret === '') {
            throw new InvalidArgumentException('JWT secret tidak boleh kosong.');
        }

        return self::encode(self::payloadForUser($user, $ttlMinutes), $secret);
    }
}
