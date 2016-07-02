    <?php

	//reporte de costos por proyecto
	
session_start();
include_once 'includes/functions.php';
sessionTimeOut();
sessioncheck(4);
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
                                             WHERE semanas.mes = ? AND cargahoras.horas > 0 and proyectos.proyecto_id_tipo NOT IN (1,2)');
    $queryProyectosConCarga->execute([$mes]);

    // Cantidad de usuarios por proyecto
    $cantUsuarioPorPoryecto = $pdo->prepare("SELECT distinct(cargahoras.id_usuario), usuarios.usuario FROM cargahoras
                                             INNER JOIN usuarios ON usuarios.id_usuario = cargahoras.id_usuario
                                             WHERE cargahoras.id_proyecto = ? AND cargahoras.id_semana in ($semanaParaQuery)");

    $sumaDeHorasPorProyecto = $pdo->prepare("SELECT sum(cargahoras.horas)/40*100 as cargaTotal FROM cargahoras 
                                             WHERE cargahoras.id_usuario = ? AND cargahoras.id_semana = ? AND cargahoras.id_proyecto = ?");
    if ($queryProyectosConCarga->rowCount() > 0) { // Si en el mes seleccionado hay carga para proyectos continuar
  

        $totalProyecto = $pdo->prepare("SELECT proyectos.proyecto,
                                              CONCAT('$ ',round(sum(cargahoras.horas)/sum(semanas.horas_habiles)*sum(usuarios.costo_semanal),0)) AS Calculo,
                                              proyectos.proyecto_id_tipo
                                            FROM cargahoras
                                              INNER JOIN semanas ON semanas.id_semana = cargahoras.id_semana
                                              INNER JOIN proyectos ON proyectos.id_proyecto = cargahoras.id_proyecto
                                              INNER JOIN usuarios ON usuarios.id_usuario = cargahoras.id_usuario
                                              INNER JOIN tipo_proyecto ON tipo_proyecto.id_tipo = proyectos.proyecto_id_tipo
                                            WHERE semanas.mes = ? and proyectos.proyecto_id_tipo not in (1,2)
                                            GROUP BY proyectos.proyecto");
        $totalProyecto->execute([$mes]);
        $totalCostoProyecto = $totalProyecto->fetchAll();

        $totalProyectoAnual = $pdo->query("SELECT proyectos.proyecto,
                                              CONCAT('$ ',round(sum(cargahoras.horas)/sum(semanas.horas_habiles)*sum(usuarios.costo_semanal),0)) AS Calculo
                                            FROM cargahoras
                                              INNER JOIN semanas ON semanas.id_semana = cargahoras.id_semana
                                              INNER JOIN proyectos ON proyectos.id_proyecto = cargahoras.id_proyecto
                                              INNER JOIN usuarios ON usuarios.id_usuario = cargahoras.id_usuario
                                            GROUP BY proyectos.proyecto");
        $costoTotalAnual = $totalProyectoAnual->fetchAll();
    }
    else {
        echo '<h3><label class="label label-danger">No se han encontrado resultados</label></h3>';
        echo '<a href="reporte.php" class="btn btn-info">Volver a buscar</a>';
        exit();
    }

}


?>
<?php if (!isset($_GET['mes'])) :?>
<div class="container">
    <div class="row">
        <div class="col-md-6">            
            <form class="form-horizontal" method="get">
                <fieldset>
                    <legend>Generar <?php if (isset($_GET['mes'])) {echo ' nuevo ';} ?>reporte</legend>
                    <div class="form-group">
                        <label for="mes" class="col-lg-2 control-label">Mes</label>
                        <div class="col-lg-10">
                            <?php if (!isset($_GET['mes'])) :?>
                            <select name="mes" id="mes">
                                <?php for ($i = 1 ; $i < 13 ; $i ++) :?>
                                    <option value="<?=$i?>" <?php if (isset($_GET['mes']) AND $_GET['mes'] == $i) { echo ' selected' ; }?>><?=$meses[$i]?></option>
                                <?php endfor;?>
                            </select>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-10 col-lg-offset-2">
                            <?php if (isset($_GET['mes'])) :?>
                            <?php else :?>
                                <button type="submit" class="btn btn-success" name="submit" value="ok">Generar reporte</button>
                            <?php endif ;?>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
<?php endif ;?>

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
            <tr <?php if ($value->proyecto_id_tipo == 5) { echo 'class="danger"'; }?>>
                <td><?=$value->proyecto ?></td>
                <td><?=$value->Calculo ?></td>
            </tr>
        <?php endforeach;?>
        <tr></tr>
        </tbody>
    </table>
    <b>Referencias:</b>
    <table class="table table-bordered" style="width: 200px;">
        <tr align="center">
            <td><i><b>Proyectos de Desarrollo</b></i></td>
        </tr>
        <tr align="center">
            <td class="danger"><i><b>Proyecto de capacitación</b></i></td>
        </tr>
    </table>
    
<?php endif;?>
<?php if (isset($_GET['mes']) AND !empty($_GET['mes'])) : ?>
    <a href="reporte.php" class="btn btn-info">Volver a buscar</a>
<?php endif;?>
<?php require_once 'includes/footer.php';?>
</div>
