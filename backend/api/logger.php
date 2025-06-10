<?php
/**
 * Sistema básico de logging para la API
 */
class Logger {
    // Niveles de log
    const DEBUG = 'DEBUG';
    const INFO = 'INFO';
    const WARNING = 'WARNING';
    const ERROR = 'ERROR';
    
    private $logFile;
    private $logLevel;
    
    /**
     * Constructor
     * 
     * @param string $logFile Ruta al archivo de log
     * @param string $logLevel Nivel mínimo de logs a registrar
     */
    public function __construct($logFile = null, $logLevel = self::INFO) {
        // Directorio de logs por defecto
        if ($logFile === null) {
            $logDir = __DIR__ . '/../logs';
            
            // Crear directorio de logs si no existe
            if (!file_exists($logDir)) {
                mkdir($logDir, 0755, true);
            }
            
            $this->logFile = $logDir . '/api_' . date('Y-m-d') . '.log';
        } else {
            $this->logFile = $logFile;
        }
        
        $this->logLevel = $logLevel;
    }
    
    /**
     * Registra un mensaje de debug
     * 
     * @param string $message Mensaje a registrar
     * @param array $context Datos adicionales
     */
    public function debug($message, array $context = []) {
        $this->log(self::DEBUG, $message, $context);
    }
    
    /**
     * Registra un mensaje informativo
     * 
     * @param string $message Mensaje a registrar
     * @param array $context Datos adicionales
     */
    public function info($message, array $context = []) {
        $this->log(self::INFO, $message, $context);
    }
    
    /**
     * Registra una advertencia
     * 
     * @param string $message Mensaje a registrar
     * @param array $context Datos adicionales
     */
    public function warning($message, array $context = []) {
        $this->log(self::WARNING, $message, $context);
    }
    
    /**
     * Registra un error
     * 
     * @param string $message Mensaje a registrar
     * @param array $context Datos adicionales
     */
    public function error($message, array $context = []) {
        $this->log(self::ERROR, $message, $context);
    }
    
    /**
     * Registra una excepción
     * 
     * @param Exception $exception Excepción a registrar
     * @param array $context Datos adicionales
     */
    public function exception($exception, array $context = []) {
        $context['exception'] = [
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString()
        ];
        
        $this->error('Exception: ' . $exception->getMessage(), $context);
    }
    
    /**
     * Registra un mensaje en el archivo de log
     * 
     * @param string $level Nivel del log
     * @param string $message Mensaje a registrar
     * @param array $context Datos adicionales
     */
    private function log($level, $message, array $context = []) {
        // Verificar si debemos registrar este nivel
        if (!$this->shouldLog($level)) {
            return;
        }
        
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'level' => $level,
            'message' => $this->interpolate($message, $context),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'method' => $_SERVER['REQUEST_METHOD'] ?? 'unknown',
            'uri' => $_SERVER['REQUEST_URI'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ];
        
        if (!empty($context)) {
            $logEntry['context'] = json_encode($context);
        }
        
        $logLine = json_encode($logEntry) . PHP_EOL;
        
        file_put_contents($this->logFile, $logLine, FILE_APPEND);
    }
    
    /**
     * Verifica si un nivel de log debe ser registrado
     * 
     * @param string $level Nivel a verificar
     * @return bool True si debe registrarse, false en caso contrario
     */
    private function shouldLog($level) {
        $levels = [
            self::DEBUG => 0,
            self::INFO => 1,
            self::WARNING => 2,
            self::ERROR => 3
        ];
        
        return $levels[$level] >= $levels[$this->logLevel];
    }
    
    /**
     * Interpola variables en el mensaje
     * 
     * @param string $message Mensaje con placeholders
     * @param array $context Variables a interpolar
     * @return string Mensaje interpolado
     */
    private function interpolate($message, array $context = []) {
        $replace = [];
        
        foreach ($context as $key => $val) {
            if (is_string($val) || is_numeric($val)) {
                $replace['{' . $key . '}'] = $val;
            }
        }
        
        return strtr($message, $replace);
    }
} 