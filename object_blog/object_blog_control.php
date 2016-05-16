<?php
/************
author: @cikeron 2016 MIT License
*/
if (file_exists("object_blog/object_blog_database.php")) {
  require("object_blog/object_blog_database.php");} //Script Objeto para el logueo de usuario. Necesario crear usuario con su metodo showPageRegistration();
else {echo'ERROR!!!';}

class object_blog_control {

	public static $conexion;
	private $tipoBD;
	public $resultados;

	public function __construct() {
	}

	public function __construct1($filesqlite){
		$this->cfg_SQLite($filesqlite);
	}

	public function cfg_SQLite($based) {
	//if (file_exists($based)) {
	//echo $based;}

	    try {
	        $this->conexion = new SQLite3($based);
	        $this->conexion->busyTimeout(5000);
	        //return($this->conexion);
	    } catch (PDOException $e) {
	        print "<p>Error: No puede conectarse con la base de datos.</p>\n";
	        print "<p>Error: " . $e->getMessage() . "</p>\n";
	        exit();
	    }
	}
	//** NO FUNCIONAL AUN****
	public function cfg_Mysql($host,$user,$pass){
	}

	public function insertarLite($consulta) {
    try {
		    $this->resultados=Database::getInstance()->getDb()->exec($consulta);
        return true;
    }catch (PDOException $e) {
        return false;
    }
		$this->conexion->close();
	}
/**
IMPORTANTE
Comprobar que el directorio y archivo sqlite tiene permisos sino darselo
sudo chown www-data blog
sudo chown www-data blog/fichero.db
*/
	public function updateLite($sentencia) {
  try{
        $this->resultados=Database::getInstance()->getDb()->exec($sentencia);
        return true;
  } catch (PDOException $e){
        return false;
  }
        $this->conexion->close();
	}


	public function borrarLite($sentencia) {
    try{
          $this->resultados=Database::getInstance()->getDb()->exec($sentencia);
          return true;
    } catch (PDOException $e){
          return false;
    }
	}

	public function listarSQL($sentencia){
    try {
				// Preparar sentencia
				$this->resultados = Database::getInstance()->getDb()->query($sentencia);
				return true;
		} catch (PDOException $e) {
				return false;
		}
	}

  /**
   * Obtiene todos las entradas con su id
   * determinado
   *
   * @param $id Identificador de la entrada
   * @return mixed
   */
  public function entradaByid($cidr)
  {
    $sentencia="SELECT * FROM entradas WHERE id='".$cidr."'";
    try {
				// Preparar sentencia
				$this->resultados = Database::getInstance()->getDb()->query($sentencia);
				return true;
		} catch (PDOException $e) {
				return false;
		}
  }
	//Funcion por si es necesaria la redireccion a alguna otra direccion web.
	public function Redirect($url, $permanent = false)
	{
	    if (headers_sent() === false)
	    {
	    	header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
	    }
	    exit();
	}
  public function redirigir($pagina) {
    echo '<script type="text/javascript">window.location = "'.$pagina.'"</script>';
  }
}

?>
