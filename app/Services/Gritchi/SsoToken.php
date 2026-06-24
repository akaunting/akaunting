<?php

namespace App\Services\Gritchi;

use RuntimeException;

class SsoToken
{
    private const ALGORITHM = 'HS256';
    private const AUDIENCE = 'akaunting';
    private const ISSUER = 'gritchi-portal';
    private const SECRET_ENV = 'GRITCHI_SSO_SECRET';
    private const DEVELOPMENT_SECRET = 'dev-gritchi-sso-secret-change-me';

    public static function decode(string $token): array
    {
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            throw new RuntimeException('Invalid token format.');
        }

        [$encoded_header, $encoded_payload, $encoded_signature] = $parts;
        $unsigned_token = $encoded_header . '.' . $encoded_payload;

        if (! hash_equals(self::sign($unsigned_token), $encoded_signature)) {
            throw new RuntimeException('Invalid token signature.');
        }

        $header = self::decodePart($encoded_header);
        $payload = self::decodePart($encoded_payload);

        if (($header['alg'] ?? null) !== self::ALGORITHM) {
            throw new RuntimeException('Invalid token algorithm.');
        }

        if (($payload['iss'] ?? null) !== self::ISSUER) {
            throw new RuntimeException('Invalid token issuer.');
        }

        if (($payload['aud'] ?? null) !== self::AUDIENCE) {
            throw new RuntimeException('Invalid token audience.');
        }

        if (empty($payload['email'])) {
            throw new RuntimeException('Token is missing an email.');
        }

        if (($payload['exp'] ?? 0) < time()) {
            throw new RuntimeException('Token has expired.');
        }

        return $payload;
    }

    private static function decodePart(string $value): array
    {
        $base64 = strtr($value, '-_', '+/');
        $base64 .= str_repeat('=', (4 - strlen($base64) % 4) % 4);
        $json = base64_decode($base64, true);

        if ($json === false) {
            throw new RuntimeException('Invalid token encoding.');
        }

        $decoded = json_decode($json, true);

        if (! is_array($decoded)) {
            throw new RuntimeException('Invalid token JSON.');
        }

        return $decoded;
    }

    private static function sign(string $unsigned_token): string
    {
        return self::base64UrlEncode(hash_hmac('sha256', $unsigned_token, self::secret(), true));
    }

    private static function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    private static function secret(): string
    {
        $secret = env(self::SECRET_ENV);

        if (! empty($secret)) {
            return $secret;
        }

        if (app()->environment('production')) {
            throw new RuntimeException(self::SECRET_ENV . ' must be set.');
        }

        return self::DEVELOPMENT_SECRET;
    }
}
