<?php

declare(strict_types=1);

namespace App;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $config = self::resolveConfig();

        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            $config['host'],
            $config['name'],
            $config['charset']
        );

        try {
            self::$connection = new PDO($dsn, $config['user'], $config['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            error_log('Database connection failed: ' . $e->getMessage());
            throw $e;
        }

        return self::$connection;
    }

    public static function disconnect(): void
    {
        self::$connection = null;
    }

    /**
     * @return array{host:string,name:string,user:string,pass:string,charset:string}
     */
    private static function resolveConfig(): array
    {
        return [
            'host' => self::getValue('DB_HOST', 'localhost'),
            'name' => self::getValue('DB_NAME', 'ban_sach'),
            'user' => self::getValue('DB_USER', 'root'),
            'pass' => self::getValue('DB_PASS', ''),
            'charset' => self::getValue('DB_CHARSET', 'utf8mb4'),
        ];
    }

    private static function getValue(string $key, string $default): string
    {
        if (defined($key)) {
            return (string)constant($key);
        }

        $envValue = getenv($key);
        if ($envValue !== false && $envValue !== null) {
            return (string)$envValue;
        }

        return $default;
    }
}
