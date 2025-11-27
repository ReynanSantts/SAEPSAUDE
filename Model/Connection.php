<?php
// /Model/Connection.php
namespace Model;

// Usamos a classe PDO nativa do PHP.
use PDO;
use PDOException;

class Connection {
    
    private static $instance = null;

    private function __construct() {}

    private function __clone() {}


    public static function getInstance() {
        if (self::$instance === null) {
            try {
                require_once __DIR__ . '/../Config/Configuration.php';
                
                $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8';
                
                self::$instance = new PDO($dsn, DB_USER, DB_PASSWORD);
                
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            } catch (PDOException $e) {
                die("Erro de conexão com o banco de dados: " . $e->getMessage());
            }
        }
        
        return self::$instance;
    }
}
