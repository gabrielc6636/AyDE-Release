<?php

//proceso de creacion de proyectos

session_start();
require_once '../includes/db.php';

if(isset($_GET['proyect']) AND !empty($_GET['proyect'])){
    $tipoProyecto = htmlspecialchars($_GET['tipoproyecto']);
    $requete = $pdo->query('SELECT proyectos.proyecto FROM proyectos');
    while ($proyectos = $requete->fetch()) {
        if ($_GET['proyect'] == $proyectos->proyecto) {
            $_SESSION['createProyect'] = "<h5><label class='label label-danger'>Error: Ya existe</label></h5>";
            header('Location: ../abmasoc.php');
            exit();
        }
    }
    $requete = $pdo->prepare('INSERT INTO proyectos SET proyectos.proyecto = ?, proyectos.proyecto_id_tipo = ?');
    $requete->execute([$_GET['proyect'],$tipoProyecto]);
    $_SESSION['createProyect'] = "<h5><label class='label label-success'>Proyecto creado correctamente <span class='glyphicon glyphicon-thumbs-up'></span></label></h5>";
    header('Location: ../abmasoc.php');
    exit();
}
else {
    $_SESSION['createProyect'] = "<h5><label class='label label-danger'>Error!</label></h5>";
    header('Location: ../abmasoc.php');
    exit();
}