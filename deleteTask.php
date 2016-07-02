<?php
/**
 * Created by PhpStorm.
 * User: U411207
 * Date: 19/05/2016
 * Time: 19:50
 */
session_start();

require_once 'includes/db.php';
$usuario = $_GET['usuario']; $semana = $_GET['semana'];
$return = "cargaHoras.php?semana=$semana&usuario=$usuario";

if (isset($_GET['proyecto'])) {
    $proyecto = $_GET['proyecto'];
    $return .= "&proyecto=$proyecto";
}

if (isset($_GET['linea'])) {
    $query = 'DELETE FROM cargahoras WHERE cargahoras.id_cargahoras = ?';
    $requete = $pdo->prepare($query);
    $requete->execute([$_GET['linea']]);
    header('Location: '. $return);
}
else {
    header("Location: $return");
    exit();
}