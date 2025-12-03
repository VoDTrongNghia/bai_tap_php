<?php
declare(strict_types=1);

namespace App\Helpers;

/**
 * Logger Class - Ghi log lỗi và hoạt động của hệ thống
 */
class Logger
{
    private const LOG_DIR = __DIR__ . '/../../logs/';
    private const ERROR_LOG = 'error.log';
    private const INFO_LOG = 'info.log';
    private const DEBUG_LOG = 'debug.log';
    
    // Log levels
    public const LEVEL_ERROR = 'ERROR';
    public const LEVEL_WARNING = 'WARNING';
    public const LEVEL_INFO = 'INFO';
    public const LEVEL_DEBUG = 'DEBUG';
    
    /**
     * Đảm bảo thư mục logs tồn tại
     */
    private static function ensureLogDir(): void
    {
        if (!is_dir(self::LOG_DIR)) {
            mkdir(self::LOG_DIR, 0755, true);
        }
    }
    
    /**
     * Ghi log vào file
     */
    private static function writeLog(string $level, string $message, array $context = [], ?string $logFile = null): void
    {
        self::ensureLogDir();
        
        // Chọn file log dựa trên level
        if ($logFile === null) {
            switch ($level) {
                case self::LEVEL_ERROR:
                case self::LEVEL_WARNING:
                    $logFile = self::ERROR_LOG;
                    break;
                case self::LEVEL_INFO:
                    $logFile = self::INFO_LOG;
                    break;
                case self::LEVEL_DEBUG:
                    $logFile = self::DEBUG_LOG;
                    break;
                default:
                    $logFile = self::ERROR_LOG;
            }
        }
        
        $logPath = self::LOG_DIR . $logFile;
        
        // Lấy thông tin về nơi gọi (caller)
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
        $caller = $backtrace[2] ?? $backtrace[1] ?? ['file' => 'unknown', 'line' => 0];
        $file = basename($caller['file'] ?? 'unknown');
        $line = $caller['line'] ?? 0;
        
        // Format timestamp
        $timestamp = date('Y-m-d H:i:s');
        
        // Format message với context
        $contextStr = '';
        if (!empty($context)) {
            $contextStr = ' | Context: ' . json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
        
        // Format log entry
        $logEntry = sprintf(
            "[%s] [%s] %s:%d | %s%s\n",
            $timestamp,
            $level,
            $file,
            $line,
            $message,
            $contextStr
        );
        
        // Ghi vào file
        file_put_contents($logPath, $logEntry, FILE_APPEND | LOCK_EX);
        
        // Nếu là ERROR, cũng ghi vào PHP error log
        if ($level === self::LEVEL_ERROR) {
            error_log($message . $contextStr);
        }
    }
    
    /**
     * Log error
     */
    public static function error(string $message, array $context = []): void
    {
        self::writeLog(self::LEVEL_ERROR, $message, $context);
    }
    
    /**
     * Log warning
     */
    public static function warning(string $message, array $context = []): void
    {
        self::writeLog(self::LEVEL_WARNING, $message, $context);
    }
    
    /**
     * Log info
     */
    public static function info(string $message, array $context = []): void
    {
        self::writeLog(self::LEVEL_INFO, $message, $context);
    }
    
    /**
     * Log debug
     */
    public static function debug(string $message, array $context = []): void
    {
        self::writeLog(self::LEVEL_DEBUG, $message, $context);
    }
    
    /**
     * Log database error
     */
    public static function dbError(string $message, array $context = []): void
    {
        $context['type'] = 'database';
        self::error($message, $context);
    }
    
    /**
     * Log authentication error
     */
    public static function authError(string $message, array $context = []): void
    {
        $context['type'] = 'authentication';
        self::error($message, $context);
    }
    
    /**
     * Log registration attempt
     */
    public static function registration(string $message, array $context = []): void
    {
        $context['type'] = 'registration';
        self::info($message, $context);
    }
    
    /**
     * Log login attempt
     */
    public static function login(string $message, array $context = []): void
    {
        $context['type'] = 'login';
        self::info($message, $context);
    }
    
    /**
     * Đọc log file (để hiển thị)
     */
    public static function readLog(string $logFile = self::ERROR_LOG, int $lines = 100): array
    {
        $logPath = self::LOG_DIR . $logFile;
        
        if (!file_exists($logPath)) {
            return [];
        }
        
        $file = file($logPath);
        if ($file === false) {
            return [];
        }
        
        // Lấy N dòng cuối cùng
        return array_slice($file, -$lines);
    }
    
    /**
     * Xóa log file cũ (giữ lại N dòng cuối)
     */
    public static function clearLog(string $logFile = self::ERROR_LOG, int $keepLines = 1000): void
    {
        $logPath = self::LOG_DIR . $logFile;
        
        if (!file_exists($logPath)) {
            return;
        }
        
        $lines = file($logPath);
        if ($lines === false || count($lines) <= $keepLines) {
            return;
        }
        
        // Giữ lại N dòng cuối
        $keep = array_slice($lines, -$keepLines);
        file_put_contents($logPath, implode('', $keep));
    }
    
    /**
     * Lấy kích thước log file
     */
    public static function getLogSize(string $logFile = self::ERROR_LOG): int
    {
        $logPath = self::LOG_DIR . $logFile;
        return file_exists($logPath) ? filesize($logPath) : 0;
    }
}

