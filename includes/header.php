<?php

if (strpos($_SERVER['REQUEST_URI'], 'cargaHoras') !== false) {
    $nomMsg = '» Carga de esfuerzo';
}
elseif (strpos($_SERVER['REQUEST_URI'], 'abmasoc') !== false){
    $nomMsg = '» Administracion';
}
elseif (strpos($_SERVER['REQUEST_URI'], 'reporte') !== false) {
    $nomMsg = '» Reporte';
}
elseif (strpos($_SERVER['REQUEST_URI'], 'listarProyectos') !== false) {
    $nomMsg = '» Lista de proyectos';
}
elseif (isset($_SESSION['user']) AND strpos($_SERVER['REQUEST_URI'], 'index.php') !== false) {
    $nomMsg = '» Home';
}
else {
    $nomMsg = '» Login';
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SRE <?=$nomMsg;?></title>
    <link rel="stylesheet" href="dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">

<body>

<div class="container">
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="index.php">SRE <span style="font-size: 12px;">Sistema de Registro de Esfuerzos</span></a>
            </div>
            <div id="navbar" class="navbar-collapse">
                <ul class="nav navbar-nav">
                    <?php if (isset($_SESSION['user'])) :?>
                        <?php if ($_SESSION['user']->user_rol < 5) :?>
                            <li <?php if (strpos($_SERVER['REQUEST_URI'], 'cargaHoras') !== false) {echo 'class="active"';} ?>><a href="cargaHoras.php">Carga de esfuerzo</a></li>
                        <?php endif; ?>
                        <?php if ($_SESSION['user']->user_rol == 5) :?>
                            <li <?php if (strpos($_SERVER['REQUEST_URI'], 'reporte') !== false) {echo 'class="active"';} ?>><a href="reporte.php">Reporte</a></li>
                        <?php endif;?>
                        <?php if ($_SESSION['user']->user_rol > 8) :?>
                            <li <?php if (strpos($_SERVER['REQUEST_URI'], 'abmasoc') !== false) {echo 'class="active"';} ?>><a href="abmasoc.php">Administracion</a></li>
                            <li <?php if (strpos($_SERVER['REQUEST_URI'], 'listarProyectos') !== false) {echo 'class="active"';} ?>><a href="listarProyectos.php">Lista de proyectos</a></li>
                        <?php endif; ?>
                    <?php endif;?>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <?php if (isset($_SESSION['user'])) :?>
                        <li><a href="javascript:void(0);"><span style="font-size: 15px;">Bienvenido <?=$_SESSION['user']->usuario;?></span></a></li>
                        <li><a href="logout.php">Logout <span class="glyphicon glyphicon-off"></span></a></li>
                    <?php endif;?>
                </ul>
            </div>
        </div>
    </nav>
    <?php if (isset($_SESSION['sessionTimeOut'])) { echo $_SESSION['sessionTimeOut'] ;} ?>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                
            </div>
        </div>
    </div>
</div>