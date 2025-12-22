<?php

namespace NSWDPC\UserForms\IpCollection;

use SilverStripe\Core\Config\Configurable;

/**
 * IP model
 * @author James
 */
class IP
{
    use Configurable;

    private static array $ip_priority = [];

    /**
     * Get the IP address based on configured priorties.
     * Each record in ip_priority is a header name, that will be prefixed with HTTP_
     */
    public static function getByPriority(): string
    {
        $headers = static::config()->get('ip_priority');
        if (!is_array($headers)) {
            return "";
        }

        // expected header names will have this prefix.
        $prefix = "HTTP_";

        $value = "";
        foreach ($headers as $header) {
            $value = "";
            if (isset($_SERVER[ $prefix . $header ])) {
                $value = trim(strip_tags((string) $_SERVER[ $prefix . $header ]));
            }

            if ($value !== '') {
                // found value
                return $value;
            }
        }

        // fall back to REMOTE_ADDR
        // @phpstan-ignore identical.alwaysTrue
        if ($value === '') {
            return $_SERVER['REMOTE_ADDR'] ?? '';
        } else {
            // nothing found
            return '';
        }
    }
}
