<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>RasPI v.1.3</title>
<style type="text/css">

</style>
</head>
<body>
<?php
    require ("index2.php");
    //usar como direccion web indexAdmin.php?action=iniciarsesion
    $applogin=new  OneFileLoginApplication();
    if ($applogin->getUserLoginStatus()) {
        echo $applogin->usuario;
    } else {
        $applogin->showPageRegistration();
    }
?>
</body>
</html>                                		                                		