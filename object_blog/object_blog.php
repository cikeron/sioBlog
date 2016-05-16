<?php
/************
author: @cikeron 2016 MIT License
*/
//************ CONTROLADOR PRINCIPAL DEL OBJETO object_blog y object_blog_view
//CONFIGURACION ESENCIAL
class object_blog {

  private $cblog='';
  private $blog_control=null;
  private $view=null;
  private $loginstatus=false;

  /**
    Constructor que crea el resto de obejtos y demás para generar el blog.
    Aqui es donde se deben añadir todas las funciones etc que se crean necesarias.
  */
  function __construct() {  
    //Esta parte es para tener un control de acceso sencillo. Para ello se usa un objeto para comprobar el acceso.
    if (file_exists("login/OneFileLoginApplication.php")) {
      require("login/OneFileLoginApplication.php"); //Script Objeto para el logueo de usuario. Necesario crear usuario con su metodo showPageRegistration();
      $applogin = new OneFileLoginApplication();
    }else {echo'ERROR!!!';}
    //global $applogin;
    if ($applogin->getUserLoginStatus()) {
      //variable para saber si se ha logueado un usuario con acceso y poder darle permisos.
      $this->loginstatus=TRUE;
    } else {
      //En este caso será un simple visitante.
      $this->loginstatus=FALSE;
    }
    //Una vez comprobado el login o acceso se continua....
    if (file_exists("object_blog/object_blog_control.php")) {
      require("object_blog/object_blog_control.php");}
    else {echo'Object ERROR!!!';}
    //Creamos el objeto blog_control y le pasamos el fichero con la BD SQLite3.
    $this->blog_control=new object_blog_control();
    $this->blog_control->__construct1('blog.sqlite');

    if (file_exists("object_blog/object_blog_view.php")) {
      require("object_blog/object_blog_view.php");
      $this->view=new object_blog_view('Simple Blog PHP Object v.0.6','Simple Blog PHP Object v.0.6 by cikeron');
      $this->view->v_control1($this->loginstatus);
      $this->view->viewcab();
    } else{
        echo 'ERROR cargando...';
    }

    //Opciones del blog
    $this->cblog= isset($_GET["cblog"]) ? $_GET["cblog"]:null;
  }// FIN CONSTRUCT

  public function create(){
    switch ($this->cblog) {
    	case 'nuevae':
        $this->view->v_nuevaentrada();
    		break;

    	case 'insertae' :
      $postautor=$_POST['edautor'];
      $postfecha=$_POST['edfecha'];
      $posttitulo=$_POST['edtitulo'];
      $value=$_POST['editor1'];
      //if ( get_magic_quotes_gpc() )
        //$value = htmlspecialchars( stripslashes((string)$value) );
      //else
        //$value = htmlspecialchars( (string)$value );
      $consulta = "INSERT INTO entradas(titulo,entrada,autor)
                  VALUES ('".$posttitulo."','".$value."','".$postautor."')";
        $retorno=$this->blog_control->insertarLite($consulta);
        if ($retorno){
          $this->blog_control->redirigir('index.php');
        } else {
          echo 'ERROR en la inserción...!!';
        }
    	break;
    //*************************EDITAR*********************
    	case 'editar':
      $cidr= isset($_GET["idr"]) ? $_GET["idr"]:null;
      if ($this->blog_control->entradaByid($cidr)){
        while ($row = $this->blog_control->resultados->fetchArray()) {
        	$titulo=$row[1];
        	$entrada=$row[2];
        	$autor=$row[3];
        	$fecha=$row[4];
        	$extra=$row[5];
          $this->view->v_editaentrada($cidr,$titulo,$autor,$fecha,$entrada);
        }
      } else {echo 'ERROR al acceder a la entrada...';}
    	break;
    //*************************FIN EDITAR****************
    //***********************EDITA***************
    	case 'edita':
        $postautor=$_POST['edautor'];
        $postfecha=$_POST['edfecha'];
        $posttitulo=$_POST['edtitulo'];
        $value=$_POST['editor1'];
        $cidr= isset($_GET["idr"]) ? $_GET["idr"]:null;
        $sentencia = "UPDATE entradas SET
                     titulo='".$posttitulo."',entrada='".$value."',autor='".$postautor."',fecha='".$postfecha."' WHERE id='".$cidr."'";
        if ($this->blog_control->updateLite($sentencia)) {
          //echo '<script type="text/javascript">window.location = "index.php"</script>';
          $this->blog_control->redirigir('index.php');
        } else {
          echo 'ERROR Actualizando entrada...';
        }
    	break;
    //**********************FIN EDITA***********
    //********************* ELIMINAR ***************
    	case 'beliminar':
      $cidr= isset($_GET["idr"]) ? $_GET["idr"]:null;
      if ($this->blog_control->entradaByid($cidr)){
        while ($row = $this->blog_control->resultados->fetchArray()) {
        	$titulo=$row[1];
        	$entrada=$row[2];
        	$autor=$row[3];
        	$fecha=$row[4];
        	$extra=$row[5];
          $this->view->v_eliminaentrada($cidr,$titulo,$autor,$fecha,$entrada);
        }
      } else {echo 'ERROR...';}
    	break;
    //********************* ELIMINAR ***************
    //********************* BELIMINA**************
    	case 'belimina':
      $cidr= isset($_GET["idr"]) ? $_GET["idr"]:null;
      $sentencia="DELETE FROM entradas WHERE id='".$_GET["idr"]."'";
      if ($this->blog_control->borrarLite($sentencia)) {
        //echo '<script type="text/javascript">window.location = "index.php"</script>';
        $this->blog_control->redirigir('index.php');
      } else {
        echo 'ERROR Eliminando entrada...';
      }
    	break;
    //********************* BELIMINA ***************
    //********************* DEFECTO: Lista Entradas****************
    	default:

           if ($this->blog_control->listarSQL('SELECT * FROM entradas ORDER BY fecha Desc')){
             while ($row = $this->blog_control->resultados->fetchArray()) {
              $cidr=$row[0];
             	$titulo=$row[1];
             	$entrada=$row[2];
             	$autor=$row[3];
             	$fecha=$row[4];
              $this->view->v_mostrarentrada($cidr,$titulo,$autor,$fecha,$entrada,$this->loginstatus);
             }
           } else {
             echo 'ERROR al listar!!!!';
           }
    		break;
    }//END switch
    $this->view->viewfooter();
  }//FIN control()
}// FIN CLASS
?>
