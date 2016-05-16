<?php
/************
author: @cikeron 2016 MIT License
*/
/**
 * Provee las constantes para conectarse a la base de datos
 * Mysql. DE MOMENTO SOLO PARA SQLite3.

define("HOSTNAME", "localhost");// Nombre del host
define("DATABASE", "nombredb"); // Nombre de la base de datos
define("USERNAME", "usuario"); // Nombre del usuario
define("PASSWORD", "password"); // Nombre de la constraseña
*/

/**
 * Clase que envuelve una instancia de la clase PDO
 * para el manejo de la base de datos
 */
class Database
{
    /**
     * Única instancia de la clase
     */
    private static $db = null;
    private $nombrefiledb='blog.sqlite';
    /**
     * Instancia de PDO
     */
    private static $pdo;

    final private function __construct()
    {
        try {
            // Crear nueva conexión PDO
            self::getDb();
        } catch (PDOException $e) {
            // Manejo de excepciones
        }
    }
    /**
     * Retorna en la única instancia de la clase
     * @return Database|null
     */
    public static function getInstance()
    {
        if (self::$db === null) {
            self::$db = new self();
        }
        return self::$db;
    }

    /**
     * Crear una nueva conexión PDO basada
     * en los datos de conexión
     * @return PDO Objeto PDO
     */
    public function getDb()
    {
        if (self::$pdo == null) {
            self::$pdo = new SQLite3($this->nombrefiledb);
            self::$pdo->busyTimeout(5000);
            /* PARA USAR CON Mysql
            self::$pdo = new PDO(
                'mysql:dbname=' . DATABASE .
                ';host=' . HOSTNAME .
                ';port:63343;', // Eliminar este elemento si es necesario
                USERNAME,
                PASSWORD,
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
            );

            // Habilitar excepciones
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            ********************/
        }
        return self::$pdo;
    }

    /**
     * Evita la clonación del objeto
     */
    final protected function __clone()
    {
    }

    function _destructor()
    {
        self::$pdo = null;
    }
}
?>
