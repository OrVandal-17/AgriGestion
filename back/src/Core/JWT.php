<?php

namespace App\Core;

/**
 * Implementation minimaliste de JSON Web Token (HS256) afin d'eviter une
 * dependance Composer sur des hebergements mutualises simples.
 */
class JWT
{
    private static function secret(): string
    {
        return env('JWT_SECRET', 'change_this_secret_in_production');
    }

    private static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function base64UrlDecode(string $data): string
    {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $data .= str_repeat('=', 4 - $remainder);
        }
        return base64_decode(strtr($data, '-_', '+/'));
    }

    public static function encode(array $payload): string
    {
        $header = ['typ' => 'JWT', 'alg' => 'HS256'];
        $payload['iat'] = time();
        $payload['exp'] = time() + (int) env('JWT_TTL', 86400);

        $segments = [
            self::base64UrlEncode(json_encode($header)),
            self::base64UrlEncode(json_encode($payload)),
        ];
        $signature = hash_hmac('sha256', implode('.', $segments), self::secret(), true);
        $segments[] = self::base64UrlEncode($signature);

        return implode('.', $segments);
    }

    /**
     * @return array|null payload decode, ou null si token invalide/expire
     */
    public static function decode(string $token): ?array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return null;
        }
        [$headerB64, $payloadB64, $signatureB64] = $parts;

        $expectedSignature = hash_hmac(
            'sha256',
            "$headerB64.$payloadB64",
            self::secret(),
            true
        );
        $actualSignature = self::base64UrlDecode($signatureB64);

        if (!hash_equals($expectedSignature, $actualSignature)) {
            return null;
        }

        $payload = json_decode(self::base64UrlDecode($payloadB64), true);
        if (!is_array($payload)) {
            return null;
        }
        if (isset($payload['exp']) && time() > $payload['exp']) {
            return null; // token expire
        }
        return $payload;
    }
}
