<?php
class Config
{
    // Settings that are the same for everyone: keep as constants
    const AUTHORIMGPATH = "./img/authors/";
    const ARTICLEIMGPATH = "./img/article/";
    const LOGPATH = "./logs";
    const FIRST_CELL_TARGET = "editArticle";
    const MIN_PW_LENGTH = 4;

    // Database settings: these differ per machine
    private static ?array $local = null;

    private static function get(string $key, string $envName, string $default): string
    {
        // Load optional personal overrides once (this file is NOT in GitHub)
        if (self::$local === null) {
            $file = __DIR__ . '/config.local.php';
            self::$local = file_exists($file) ? require $file : [];
        }

        // 1. Environment variable (this is what Docker provides)
        $env = getenv($envName);
        if ($env !== false && $env !== '') {
            return $env;
        }

        // 2. Personal override file, then 3. the XAMPP-style default
        return self::$local[$key] ?? $default;
    }

    public static function servername(): string { return self::get('servername', 'DB_HOST', 'localhost'); }
    public static function username(): string   { return self::get('username',   'DB_USER', 'root'); }
    public static function password(): string   { return self::get('password',   'DB_PASSWORD', ''); }
    public static function db(): string         { return self::get('db',         'DB_NAME', 'wiki'); }
}