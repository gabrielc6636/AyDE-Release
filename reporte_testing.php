<?php
/**
 * Created by PhpStorm.
 * User: U411207
 * Date: 16/05/2016
 * Time: 20:23
 */

require_once 'includes/db.php';
require_once 'includes/header.php';
$meses = array(
    1 => "Enero",
    2 => "Febrero",
    3 => "Marzo",
    4 => "Abril",
    5 => "Mayo",
    6 => "Junio",
    7 => "Julio",
    8 => "Agosto",
    9 => "Septiembre",
    10 => "Octubre",
    11 => "Noviembre",
    12 => "Diciembre"
);
if (isset($_GET['mes'],$_GET['submit']) AND !empty($_GET['mes']) AND !empty($_GET['submit'])) {
    $mes = $_GET['mes'];
    echo '<div class="container"><div class="row"><div class="col-md-7">';
    echo '<h4>Reporte para el mes de <b>'.$meses[$mes].'</b></h4>';

    //*****************************************************//
    $querySemanasDelMes = $pdo->prepare('SELECT semanas.id_semana, semanas.semana FROM semanas WHERE semanas.mes = ?');
    $querySemanasDelMes->execute([$mes]);
    $semXmes = $querySemanasDelMes->fetchAll();
    $semanaParaQuery = '';

    for ($semana = 0; $semana < count($semXmes); $semana++) {
        $semanaParaQuery .= $semXmes[$semana]->id_semana . ',';
    }
    $semanaParaQuery = substr($semanaParaQuery, 0, -1);

    // --------------------------------------------------------------------------------------- //
    $queryProyectosConCarga = $pdo->prepare('SELECT distinct(proyectos.id_proyecto), proyectos.proyecto FROM proyectos
                                             INNER JOIN cargahoras ON cargahoras.id_proyecto = proyectos.id_proyecto
                                             INNER JOIN semanas ON semanas.id_semana = cargahoras.id_semana
                                             WHERE semanas.mes = ? AND cargahoras.horas > 0');
    $queryProyectosConCarga->execute([$mes]);

    // Cantidad de usuarios por proyecto
    $cantUsuarioPorPoryecto = $pdo->prepare("SELECT distinct(cargahoras.id_usuario), usuarios.usuario FROM cargahoras
                                             INNER JOIN usuarios ON usuarios.id_usuario = cargahoras.id_usuario
                                             WHERE cargahoras.id_proyecto = ? AND cargahoras.id_semana in ($semanaParaQuery)");

    $sumaDeHorasPorProyecto = $pdo->prepare("SELECT sum(cargahoras.horas)/40*100 as cargaTotal FROM cargahoras 
                                             WHERE cargahoras.id_usuario = ? AND cargahoras.id_semana = ? AND cargahoras.id_proyecto = ?");
    if ($queryProyectosConCarga->rowCount() > 0) { // Si en el mes seleccionado hay carga para proyectos continuar
        echo '<table class="table table-bordered">';
        echo '<thead align="center">';
        echo '<tr><th>Proyecto</th><th>Usuario</th>';
        foreach ($semXmes as $key => $value) {
            echo "<th>$value->semana</th>";
        }
        echo '</tr>';
        echo '</thead>';
        // ** Armado el body ** //
        echo '<tbody>';
        // Listar Proyecto
        while ($proyecto = $queryProyectosConCarga->fetch()) {
            $cantUsuarioPorPoryecto->execute([$proyecto->id_proyecto]);
            echo '<tr><td rowspan="' . $cantUsuarioPorPoryecto->rowCount() . '">' . $proyecto->proyecto . '</td>';
            while ($users = $cantUsuarioPorPoryecto->fetch()) {
                echo '<td>' . $users->usuario . '</td>';
                // Hago la suma de las horas
                foreach ($semXmes as $key => $value) {
                    $sumaDeHorasPorProyecto->execute([$users->id_usuario, $value->id_semana, $proyecto->id_proyecto]);
                    while ($cargaTotal = $sumaDeHorasPorProyecto->fetch()) {
                        echo "<td align='center'>$cargaTotal->cargaTotal</td>";
                    }
                }
                echo '</tr>';
            }
        }

        echo '</tbody>';
        $totalProyecto = $pdo->prepare("SELECT proyectos.proyecto,
                                              CONCAT('$ ',round(sum(cargahoras.horas)/sum(semanas.horas_habiles)*sum(usuarios.costo_semanal),2)) AS Calculo
                                            FROM cargahoras
                                              INNER JOIN semanas ON semanas.id_semana = cargahoras.id_semana
                                              INNER JOIN proyectos ON proyectos.id_proyecto = cargahoras.id_proyecto
                                              INNER JOIN usuarios ON usuarios.id_usuario = cargahoras.id_usuario
                                            WHERE semanas.mes = ?
                                            GROUP BY proyectos.proyecto");
        $totalProyecto->execute([$mes]);
        $totalCostoProyecto = $totalProyecto->fetchAll();
    }
    else {
        echo '<h3><label class="label label-danger">No se han encontrado resultados</label></h3>';
        echo '<a href="reporte.php" class="btn btn-info">Volver a buscar</a>';
        exit();
    }

}
else {

}
?>
<?php if (!isset($_GET['mes'])) :?>
<div class="container">
    <div class="row">
        <div class="col-md-6">
<?php endif; ?>
            <a href="listarProyectos.php" target="_blank">Lista Proyectos</a>
            <a href="cargaHoras.php" target="_blank">Index</a>
            <form class="form-horizontal" method="get">
                <fieldset>
                    <legend>Generar <?php if (isset($_GET['mes'])) {echo ' nuevo ';} ?>reporte</legend>
                    <div class="form-group">
                        <label for="proyect" class="col-lg-2 control-label">Proyecto</label>
                        <div class="col-lg-10">
                            <select name="mes" id="mes">
                                <?php for ($i = 1 ; $i < 13 ; $i ++) :?>
                                    <option value="<?=$i?>" <?php if (isset($_GET['mes']) AND $_GET['mes'] == $i) { echo ' selected' ; }?>><?=$meses[$i]?></option>
                                <?php endfor;?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-10 col-lg-offset-2">
                            <?php if (isset($_GET['mes'])) : ?>
                                <a href="reporte_testing.php" class="btn btn-default">Limpiar</a>
                            <?php endif;?>
                            <button type="submit" class="btn btn-success" name="submit" value="ok">Generar reporte</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>
<?php if (isset($_GET['mes']) AND !empty($_GET['mes'])):?>
<table class="table table-bordered">
    <thead>
    <tr>
        <td>Proyecto</td>
        <td>Costo</td>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($totalCostoProyecto as $item => $value) :?>
            <tr>
                <td><?=$value->proyecto ?></td>
                <td><?=$value->Calculo ?></td>
            </tr>
        <?php endforeach;?>
    <tr></tr>
    </tbody>
</table>
<?php endif;?>
<?php require_once 'includes/footer.php';?>
