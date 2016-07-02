<?php
/**
 * Created by PhpStorm.
 * User: U411207
 * Date: 20/05/2016
 * Time: 11:33
 */

session_start();
require_once 'includes/db.php';
$usuario = $_GET['usuario']; $semana = $_GET['semana'];$lineaModif = $_GET['linea'];
$return = "cargaHoras.php?semana=$semana&usuario=$usuario&lineaModif=$lineaModif";

if (isset($_GET['proyecto'])) {
    $proyecto = $_GET['proyecto'];
    $return .= "&proyecto=$proyecto";
}
if (isset($_GET['linea'],$_GET['nuevaCarga']) AND $_GET['nuevaCarga'] !== $_GET['cargaActual']) {
    $query = 'UPDATE cargahoras SET horas = ? WHERE cargahoras.id_cargahoras = ?';
    $requete = $pdo->prepare($query);
    $requete->execute([$_GET['nuevaCarga'],$_GET['linea']]);
    header('Location: '. $return);
}
else {
    header("Location: $return");
    exit();
}