<?php
/**
 * JCA ERP - Configuração do Banco de Dados
 * 
 * @package JCA_ERP
 * @version 1.0.0
 */

// Configurações do Banco de Dados
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'jca_erp');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_CHARSET', getenv('DB_CHARSET') ?: 'utf8mb4');

/**
 * Classe Database - Gerencia conexão com MySQL
 */
class Database
{
    private static $instance = null;
    private $conn;

    /**
     * Construtor privado (Singleton)
     */
    private function __construct()
    {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET
            ];

            // Suporte para SSL (necessário para TiDB Cloud em alguns ambientes)
            if (getenv('DB_SSL') === 'true' || getenv('DB_SSL') === '1') {
                $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
            }

            $this->conn = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Em produção não exibir detalhes técnicos, mas por enquanto mantemos para debug
            die("Erro de conexão ao banco de dados: " . $e->getMessage());
        }
    }

    /**
     * Retorna instância única da classe (Singleton)
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Retorna a conexão PDO
     */
    public function getConnection()
    {
        return $this->conn;
    }

    /**
     * Previne clonagem
     */
    private function __clone()
    {
    }

    /**
     * Previne unserialize
     */
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize singleton");
    }
}

/**
 * Função helper para obter conexão
 */
function getDB()
{
    return Database::getInstance()->getConnection();
}

