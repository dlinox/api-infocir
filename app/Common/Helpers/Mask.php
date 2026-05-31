<?php

namespace App\Common\Helpers;

class Mask
{
    public static function email(?string $email): ?string
    {
        if (empty($email) || !str_contains($email, '@')) return $email;

        [$local, $domain] = explode('@', $email, 2);
        $localMasked = self::partial($local, 3);

        $dotPos = strrpos($domain, '.');
        if ($dotPos === false) {
            return $localMasked . '@' . self::partial($domain, 2);
        }
        $domainName = substr($domain, 0, $dotPos);
        $tld        = substr($domain, $dotPos);
        return $localMasked . '@' . self::partial($domainName, 2) . $tld;
    }

    public static function phone(?string $phone): ?string
    {
        if (empty($phone)) return $phone;
        $len = strlen($phone);
        if ($len <= 4) return str_repeat('*', $len);
        return substr($phone, 0, 1) . str_repeat('*', $len - 4) . substr($phone, -3);
    }

    public static function name(?string $name): ?string
    {
        if (empty($name)) return $name;
        $parts = preg_split('/\s+/', trim($name));
        $masked = array_map(fn ($part) => self::partial($part, 2), $parts);
        return implode(' ', $masked);
    }

    public static function username(?string $username): ?string
    {
        if (empty($username)) return $username;
        return self::partial($username, 3);
    }

    private static function partial(string $value, int $visible): string
    {
        $len = strlen($value);
        if ($len <= $visible) return $value . str_repeat('*', max(0, $visible - $len + 1));
        return substr($value, 0, $visible) . str_repeat('*', max(2, $len - $visible));
    }
}
