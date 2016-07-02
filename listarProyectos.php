<?php

//tabla de gente asignada a proyectos

session_start();
include_once 'includes/functions.php';
sessioncheck(8);
require_once 'includes/db.php';

//queries para definir proyectos de proyectos, cantidad gente por proyecto, desarrolladores

$reqProyectos = $pdo->query('SELECT distinct(asignacion.id_proyecto), proyectos.proyecto, proyectos.proyecto_id_tipo FROM asignacion INNER JOIN proyectos ON proyectos.id_proyecto = asignacion.id_proyecto WHERE asignacion.id_proyecto not in (1,2)');
$proyectosAsignados = $reqProyectos->fetchAll();

$reqCountUsers = $pdo->prepare("SELECT count(asignacion.id_proyecto) AS Cant FROM asignacion WHERE id_proyecto = ?");

$reqUsers = $pdo->prepare("SELECT asignacion.id_usuario, usuarios.usuario FROM asignacion INNER JOIN usuarios ON usuarios.id_usuario = asignacion.id_usuario WHERE asignacion.id_proyecto = ?");
require_once 'includes/header.php';
?>
<div class="container">
    <div class="col-md-4">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Proyecto</th>
                <th>Usuario</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($proyectosAsignados as $key => $value) :?>
                <?php $reqCountUsers->execute([$value->id_proyecto]);?>
                <?php $count = $reqCountUsers->fetch();?>
                <?php if ($count->Cant == 1) :?>
                    <?php $reqUsers->execute([$value->id_proyecto]); $user = $reqUsers->fetch();?>
                    <tr>
                        <td <?php if ($value->proyecto_id_tipo == 5):?> class="danger" <?php endif;?>>
                            <?=$value->proyecto?>
                        </td>
                        <td>
                            <?=$user->usuario;?>
                        </td>
                    </tr>
                <?php else :?>
                    <?php $reqUsers->execute([$value->id_proyecto]); $user = $reqUsers->fetchAll();?>
                    <tr>
                        <td rowspan="<?=$count->Cant;?>" <?php if ($value->proyecto_id_tipo == 5):?> class="danger" <?php endif;?>>
                            <?=$value->proyecto;?>
                        </td>
                        <td>
                            <?=$user[0]->usuario;?>
                        </td>
                    </tr>
                    <?php for($i = 1 ; $i < $count->Cant ; $i++) :?>
                    <tr>
                        <td>
                            <?=$user[$i]->usuario;?>
                        </td>
                    </tr>
                    <?php endfor;?>
                <?php endif;?>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<div class="row">
    <b>Referencias:</b>
    <table class="table table-bordered" style="width: 200px;">
        <tr align="center">
            <td><i><b>Proyectos de Desarrollo</b></i></td>
        </tr>
        <tr align="center">
            <td class="danger"><i><b>Proyecto de capacitación</b></i></td>
        </tr>
    </table>
    </div>
<?php
die();
$requete = $pdo->query('SELECT * FROM proyectos LEFT JOIN tipo_proyecto ON id_tipo = proyectos.proyecto_id_tipo');
    $proyectos = $requete->fetchAll();
$requete = $pdo->query('SELECT distinct(asignacion.id_usuario)
                        FROM asignacion');
    $usuarios = $requete->fetchAll();
    var_dump($usuarios);
$requete = $pdo->query('SELECT usuarios.usuario, proyectos.proyecto, proyectos.proyecto_id_tipo, proyectos.id_proyecto FROM asignacion
                        LEFT JOIN proyectos ON proyectos.id_proyecto = asignacion.id_proyecto
                        LEFT JOIN usuarios ON usuarios.id_usuario = asignacion.id_usuario
                        ORDER BY proyectos.proyecto ASC');
    $asignacion = $requete->fetchAll();

$selectCount = $pdo->prepare("SELECT count(asignacion.id_asignacion) AS Cuenta FROM asignacion WHERE asignacion.id_proyecto = ?");
?>

?>
<div class="container">
    <div class="row">
        <div class="col-md-5 hidden">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Code</th>
                </tr>
                </thead>
                <tbody>
                <?php for ($i = 0 ; $i<count($proyectos) ; $i++) :?>
                    <tr>
                        <td><?=$proyectos[$i]->id_proyecto ; ?></td>
                        <td><?= $proyectos[$i]->proyecto ;?></td>
                        <td><?= $proyectos[$i]->tipo_description;?></td>
                        <td><?= $proyectos[$i]->proyecto_id_tipo;?></td>
                    </tr>
                <?php endfor; ?>
                </tbody>
            </table>
        </div>

    <div class="col-md-3 hidden">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID_usuario</th>
                <th>Nombre de usuario</th>
            </tr>
            </thead>
            <tbody>
            <?php for ($i = 0 ; $i<count($usuarios) ; $i++) :?>
                <tr>
                    <td><?=$usuarios[$i]->id_usuario ; ?></td>
                    <td><?= $usuarios[$i]->usuario ;?></td>
                </tr>
            <?php endfor; ?>
            </tbody>
        </table>
    </div>
    
    </div>


<?php require_once 'includes/footer.php';?>