<?php
/************
author: @cikeron 2016 MIT License
*/

/*
  Para esta vista o mejor dicho para el diseño de esta web se ha utilizado el framework
  MDL https://getmdl.io/index.html
  y como añadido para editar las entradas y demás CKeditor http://ckeditor.com

  Esta es la clase mas enrebesada, debido a que hay que primero tratar el diseño
  web en HTML y despues trasladarlo aquí. Y toooodo depende del framwork que se use etc...
  En un futuro a ver como hago esto y lo abstraigo a modo de objeto template.
*/
class object_blog_view {

private $title="Checik MDL Blog v.0.62";
private $description="A front-end template that helps you build fast, modern mobile web apps.";
//Variable que se usara para operar el codigo html y mostrarlo.
public $html='';

//En el constructor se reemplazan las cadenas %%loquesea%%
public $htmlcab='
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="%%descipcion%%">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
<title>%%title%%</title>

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="icon" sizes="192x192" href="images/android-desktop.png">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Material Design Lite">
    <link rel="apple-touch-icon-precomposed" href="images/ios-desktop.png">

    <!-- Tile icon for Win8 (144x144 + tile color) -->
    <meta name="msapplication-TileImage" content="images/touch/ms-touch-icon-144x144-precomposed.png">
    <meta name="msapplication-TileColor" content="#3372DF">

    <link rel="shortcut icon" href="images/favicon.png">

    <!-- SEO: If your mobile URL is different from the desktop URL, add a canonical link to the desktop page https://developers.google.com/webmasters/smartphone-sites/feature-phones -->
    <!--
    <link rel="canonical" href="http://www.example.com/">
    -->

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.1.3/material.indigo-deep_orange.min.css" />
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/modal.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
    <style>
    #view-source {
      position: fixed;
      display: block;
      right: 0;
      bottom: 0;
      margin-right: 40px;
      margin-bottom: 40px;
      z-index: 900;
    }

    </style>
<script type="text/javascript">

</script>
</head>
<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
<header class="mdl-layout__header">
  <div class="mdl-layout__header-row">
    <span class="mdl-layout-title"><a href="index.php">%%title%%</a></span>
  </div>

      <div class="mdl-layout__tab-bar mdl-js-ripple-effect mdl-color--primary-dark">
        <a href="#overview" class="mdl-layout__tab is-active">Blog</a>
        <a href="#features" class="mdl-layout__tab">Información</a>
      </div>

  </header>
  <div class="mdl-layout__drawer">
    <span class="mdl-layout-title">Opciones</span>
';



private $htmlfooter='
          <section class="section--footer mdl-color--white mdl-grid">
            <div class="section__circle-container mdl-cell mdl-cell--2-col mdl-cell--1-col-phone">
              <div class="section__circle-container__circle mdl-color--accent section__circle--big"></div>
            </div>
            <div class="section__text mdl-cell mdl-cell--4-col-desktop mdl-cell--6-col-tablet mdl-cell--3-col-phone">
              <h5>Lorem ipsum dolor sit amet</h5>
              Qui sint ut et qui nisi cupidatat. Reprehenderit nostrud proident officia exercitation anim et pariatur ex.
            </div>
            <div class="section__circle-container mdl-cell mdl-cell--2-col mdl-cell--1-col-phone">
              <div class="section__circle-container__circle mdl-color--accent section__circle--big"></div>
            </div>
            <div class="section__text mdl-cell mdl-cell--4-col-desktop mdl-cell--6-col-tablet mdl-cell--3-col-phone">
              <h5>Lorem ipsum dolor sit amet</h5>
              Qui sint ut et qui nisi cupidatat. Reprehenderit nostrud proident officia exercitation anim et pariatur ex.
            </div>
          </section>

        </div>

        <div class="mdl-layout__tab-panel" id="features">
          <section class="section--center mdl-grid mdl-grid--no-spacing">
            <div class="mdl-cell mdl-cell--12-col">
              <h4>Información</h4>
              Información extendida acerda de este Blog.
              <p>
                Poco puedo contar, dado la sencillez del mismo, salvo que para realizarlo se ha utilizado el framework <a href="http://www.getmdl.io" target="blank">MDL</a>, un poco de php para administrar las entradas en la base de datos y gestionarlas. Como SGBD se ha usado SQLite, dado el volumen estimado de carga necesaria y por el rendimiento necesario, ya que fué pensado originalmente para implantarse en una simple raspberry pi model B.
              </p>
            </div>
          </section>
        </div>
        <footer class="mdl-mega-footer">
          <!--div class="mdl-mega-footer--middle-section">
            <div class="mdl-mega-footer--drop-down-section">
              <input class="mdl-mega-footer--heading-checkbox" type="checkbox" checked>
              <h1 class="mdl-mega-footer--heading">Features</h1>
              <ul class="mdl-mega-footer--link-list">
                <li><a href="#">About</a></li>
                <li><a href="#">Terms</a></li>
                <li><a href="#">Partners</a></li>
                <li><a href="#">Updates</a></li>
              </ul>
            </div>
            <div class="mdl-mega-footer--drop-down-section">
              <input class="mdl-mega-footer--heading-checkbox" type="checkbox" checked>
              <h1 class="mdl-mega-footer--heading">Details</h1>
              <ul class="mdl-mega-footer--link-list">
                <li><a href="#">Spec</a></li>
                <li><a href="#">Tools</a></li>
                <li><a href="#">Resources</a></li>
              </ul>
            </div>
            <div class="mdl-mega-footer--drop-down-section">
              <input class="mdl-mega-footer--heading-checkbox" type="checkbox" checked>
              <h1 class="mdl-mega-footer--heading">Technology</h1>
              <ul class="mdl-mega-footer--link-list">
                <li><a href="#">How it works</a></li>
                <li><a href="#">Patterns</a></li>
                <li><a href="#">Usage</a></li>
                <li><a href="#">Products</a></li>
                <li><a href="#">Contracts</a></li>
              </ul>
            </div>
            <div class="mdl-mega-footer--drop-down-section">
              <input class="mdl-mega-footer--heading-checkbox" type="checkbox" checked>
              <h1 class="mdl-mega-footer--heading">FAQ</h1>
              <ul class="mdl-mega-footer--link-list">
                <li><a href="#">Questions</a></li>
                <li><a href="#">Answers</a></li>
                <li><a href="#">Contact us</a></li>
              </ul>
            </div>
          </div>
          <div class="mdl-mega-footer--bottom-section">
            <div class="mdl-logo">
              More Information
            </div>
            <ul class="mdl-mega-footer--link-list">
              <li><a href="https://developers.google.com/web/starter-kit/">Web Starter Kit</a></li>
              <li><a href="#">Help</a></li>
              <li><a href="#">Privacy and Terms</a></li>
            </ul>
          </div-->
        </footer>
      </main>
    </div>

<!-- Modal formulario contacto-->
<div id="openModal" class="modalDialog">
    <div>
    	<a href="#close" title="Cerrar" class="closemodal">X</a>
		<h2>Contactar...</h2>
		<div class="divmodales" id="form-main">
                        <form id="loginform" class="form-horizontal" role="form" method="post" action="index.php" name="loginform">

                              <div class="mdl-textfield mdl-js-textfield">
    							<input class="mdl-textfield__input" id="login_input_username" type="text" name="user_name" value="">
    							<label class="mdl-textfield__label" for="login_input_username">Usuario o email</label>
  							  </div>
                              <div class="mdl-textfield mdl-js-textfield">
    							<input class="mdl-textfield__input" id="login_input_password" type="password" name="user_password" value="" >
    							<label class="mdl-textfield__label" for="login_input_password">Password</label>
  							  </div>
                                <div style="margin-top:10px" class="form-group">
                                    <!-- Button -->
                                    <div class="col-sm-12 controls">
                                      <input type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" name="login" value="Entrar" />
                                    </div>
                                </div>

                            </form>
		</div>
	</div>
</div>
    <script src="https://code.getmdl.io/1.1.3/material.min.js"></script>
  </body>
</html>
';

function __construct(){
  $args = func_get_args();
  $nargs = func_num_args();
  //Esto para si sobrecargamos el contructor con parametros. 2 concretamente.
  switch($nargs)
    {
    case 0:
        // Si no posee parametros ir al constructor1
      self::__construct1();
      break;
    case 2:
      // Si posee 3 parametros in al constructor2
      self::__construct2($args[0], $args[1]);
      break;
    }
}
public function __construct1(){
  $this->html=str_replace('%%title%%',$this->title,$this->htmlcab);
  $this->html=str_replace('%%descripcion%%',$this->descripcion,$this->html);
//  $this->html=$this->htmlcab;
}
public function __construct2($title,$descripcion) {
  //$this->html=$this->htmlcab;
  if ($title!=''){
    $this->html=str_replace('%%title%%',$title,$this->htmlcab);
  }
  if ($descripcion!=''){
    $this->html=str_replace('%%descripcion%%',$descripcion,$this->html);
  }

}
public function v_control1($control){
	if ($control===TRUE) {
    $this->html=$this->html.'
    <a href="index.php?p=blog&cblog=nuevae" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" data-toggle="modal">Nueva entrada</a>
    <a href="index.php?p=blog" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" data-toggle="modal">Regargar</a>
    <a href="#ModalInfo" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" data-toggle="modal">Informacion</a>
    <a href="#modalAyuda" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" data-toggle="modal">Ayuda</a>
    <a href="index.php?action=logout" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" data-toggle="modal">Cerrar Sesion</a>
    ';
		} else {
        $this->html=$this->html.
        '<a class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" href="#openModal">
          Login
          </a>';
		}//FIN ELSE
    $this->html=$this->html.
    '	  </div>
    <main class="mdl-layout__content">
        <div class="mdl-layout__tab-panel is-active" id="overview">';
}


public function v_mostrarentrada($cidr,$titulo,$autor,$fecha,$entrada,$loginstatus){
  echo'
  <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
              <div class="mdl-card mdl-cell mdl-cell--12-col">
                <div class="mdl-card__supporting-text">';
  echo '	            <h4>'.$titulo.'</h4>
           '.$entrada.'';
  echo '              </div>
                <div class="mdl-card__actions">';

  if ($loginstatus) {
  echo '<a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" href="index.php?p=blog&cblog=beliminar&idr='.$cidr.'">
    BORRAR
  </a>
  <a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" href="index.php?p=blog&cblog=editar&idr='.$cidr.'">
    EDITAR
  </a><br><div class="" align="right">'.$fecha.' - <i class="material-icons">person</i>'.$autor.'</div>';
  } else {
  echo '        <div class="" align="right">'.$fecha.' - <i class="material-icons">person</i>'.$autor.'</div>';
  }
  //                <a href="#" class="mdl-button">Leer más...</a>

  echo '              </div>
              </div>
              <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" id="btn4">
                <i class="material-icons">more_vert</i>
              </button>
              <ul class="mdl-menu mdl-js-menu mdl-menu--bottom-right" for="btn4">
                <li class="mdl-menu__item"><a class="dialog-button">Compartir</a></li>
                <!--li class="mdl-menu__item" disabled>Ipsum</li>
                <li class="mdl-menu__item">Dolor</li-->
              </ul>
  </section>
  ';

}
public function v_nuevaentrada(){
  echo '
  <script src="ckeditor/ckeditor.js"></script>
<form action="index.php?p=blog&cblog=insertae" method="post">
<h3>Nueva entrada</h3>
  <p>
    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    <label class="mdl-textfield__label" for="edtitulo">
      Titulo:
    </label>
    <input class="mdl-textfield__input" type="text" id="edtitulo" name="edtitulo">
    </div>
    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    <label class="mdl-textfield__label" for="edautor">
      Autor:
    </label>
    <input class="mdl-textfield__input" type="text" id="edautor" name="edautor">
    </div>
    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    <label class="mdl-textfield__label" for="edfecha">
      Fecha:
    </label>
    <input class="mdl-textfield__input" type="text" id="edfecha" name="edfecha">
    </div>
  </p>
  <p>
    <label for="editor1">
      Entrada:
    </label>
    <textarea class="ckeditor" cols="80" id="editor1" name="editor1" rows="10">
    </textarea>
  </p>
  <p>
    <input class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" type="submit" value="Enviar">
  <a href="index.php?p=blog" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" data-toggle="modal">Cancelar</a>
  </p>
</form>
  ';
}
public function v_editaentrada($cidr,$titulo,$autor,$fecha,$entrada){
  echo '
  <script src="ckeditor/ckeditor.js"></script>
<form action="index.php?p=blog&cblog=edita&idr='.$cidr.'" method="post">
<h3>Editar entrada</h3>
  <p>
  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    <label class="mdl-textfield__label" for="edtitulo">
      Titulo:
    </label>
    <input class="mdl-textfield__input" type="text" id="edtitulo" name="edtitulo" value="'.$titulo.'">
    </div>
    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    <label class="mdl-textfield__label" for="edautor">
      Autor:
    </label>
    <input class="mdl-textfield__input" type="text" id="edautor" name="edautor" value="'.$autor.'">
    </div>
    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    <label class="mdl-textfield__label" for="edfecha">
      Fecha:
    </label>
    <input class="mdl-textfield__input" type="text" id="edfecha" name="edfecha" value="'.$fecha.'">
    </div>
  </p>
  <p>
    <label for="editor1">
      Entrada:
    </label>
    <textarea class="ckeditor" cols="80" id="editor1" name="editor1" rows="10">
    '.$entrada.'
    </textarea>
  </p>
  <p>
    <input class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" type="submit" value="Enviar">
  <a href="index.php?p=blog" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" data-toggle="modal">Cancelar</a>

  </p>
</form>
  ';
}

public function v_eliminaentrada($cidr,$titulo,$autor,$fecha,$entrada){
  echo '<section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
          <div class="mdl-card mdl-cell mdl-cell--12-col">
          <div class="mdl-card__supporting-text">
          <h4>Eliminación de entrada</h4>
      <p> Una vez eliminada la entrada no se podra recuperar!!.</p>
  <script src="ckeditor/ckeditor.js"></script>
<form class="form-horizontal" action="index.php?p=blog&cblog=belimina&idr='.$cidr.'" method="post">
  <p>
    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    <label class="mdl-textfield__label" for="edtitulo">
      Titulo:
    </label>
    <input class="mdl-textfield__input" type="text" id="edtitulo" name="edtitulo" value="'.$titulo.'">
    </div>
    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    <label class="mdl-textfield__label" for="edautor">
      Autor:
    </label>
    <input class="mdl-textfield__input" type="text" id="edautor" name="edautor" value="'.$autor.'">
    </div>
    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    <label class="mdl-textfield__label" for="edfecha">
      Fecha:
    </label>
    <input class="mdl-textfield__input" type="text" id="edfecha" name="edfecha" value="'.$fecha.'">
    </div>
  </p>
  <p>
    <label for="editor1">
      Entrada:
    </label>
    <textarea class="ckeditor" cols="80" id="editor1" name="editor1" rows="15">
    '.$entrada.'
    </textarea>
  </p>
  </div>
    <div class="mdl-card__actions">
    <input type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" name="register" value="ELIMINAR" />
  <a href="index.php?p=blog" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" data-toggle="modal">Cancelar</a>
  </div>
</form>
</div>
</section>';
}

public function viewcab() {
  echo $this->html;
}
public function viewfooter() {
  echo $this->htmlfooter;
}
}//END CLASS


?>
