<?php
/**
 * Created by PhpStorm.
 * User: U411207
 * Date: 20/05/2016
 * Time: 14:00
 */
function sessioncheck($rol) {
    if (session_status() == PHP_SESSION_ACTIVE) {
        if (!isset($_SESSION['user'])){
            $_SESSION['noAuth'] = '<h4><label class="label label-info">No puedes acceder a esta página <strong>logueate</strong></label></h4>';
            header('Location: index.php');
            exit();
        }
        elseif ($_SESSION['user']->user_rol < $rol) {
            $_SESSION['noAuth'] = '<h4><label class="label label-warning">No estas autorizado a acceder a esta pagina.</label></h4>';
            header('Location: index.php');
            exit();
        }
    }
}

function sessionTimeOut() {
    /*
    $time = $_SERVER['REQUEST_TIME'];
    $timeout_duration = 10; /*** 15 minutos de conexion ***
    if (isset($_SESSION['LAST_ACTIVITY']) && ($time - $_SESSION['LAST_ACTIVITY']) > $timeout_duration AND isset($_SESSION['user'])) {
        session_unset();
        session_destroy();
        session_start();
        $_SESSION['sessionTimeOut'] = '<h4><label class="label label-warning">La sesión ha caducado por no haberse registrado actividad durante los ultimos 15 minutos</label></h4>';
        header('Location: index.php');
        exit();
    }
    $_SESSION['LAST_ACTIVITY'] = $time;
    */
}