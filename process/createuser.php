<?php

//proceso de creacion y auto-asignacion de nuevos desarrolladores a los proyectos INTERNO y AUSENCIA
session_start();
require_once '../includes/db.php';

if(isset($_GET['user'],$_GET['sueldo']) AND !empty($_GET['user']) AND !empty([$_GET['sueldo']])){
    $requete = $pdo->query('SELECT usuarios.usuario FROM usuarios');
    while ($usuarios = $requete->fetch()) {
        if ($_GET['user'] == $usuarios->usuario) {
            $_SESSION['createUser'] = "<h5><label class='label label-danger'>Error: Ya existe usuario</label></h5>";
            header('Location: ../abmasoc.php');
            exit();
        }
    }
    /*
    creacion de usuario formulas de calculo de
    COSTO = SUELDO * 13 / 12 * 1,3 (esto es fijo)
    COSTO SEMANAL = COSTO / 4
    */
    $requete = $pdo->prepare('INSERT INTO usuarios SET usuarios.usuario = ?, usuarios.sueldo = ?, usuarios.costo = ?, usuarios.costo_semanal = ?');
    $costo = round($_GET['sueldo'] * 13 / 12 * 1.3);
    $requete->execute([$_GET['user'],$_GET['sueldo'], $costo, round($costo/4)]);
    $lastOrderId = $pdo->lastInsertId();
    
    $reqAsocProyect = $pdo->prepare('INSERT INTO asignacion (id_proyecto, id_usuario) VALUES (1, :userId), (2, :userId)'); // ACA SE MODIFICAN LOS PROYECTO AUSENCIAS Y CAPA INTERNA
    $reqAsocProyect->execute(["userId" => $lastOrderId]);

    $_SESSION['createUser'] = "<h5><label class='label label-success'>Usuario creado correctamente <span class='glyphicon glyphicon-thumbs-up'></span></label></h5>";
    header('Location: ../abmasoc.php');
    exit();
}
else {
    $_SESSION['createUser'] = "<h5><label class='label label-danger'>Error!</label></h5>";
    header('Location: ../abmasoc.php');
    exit();
}