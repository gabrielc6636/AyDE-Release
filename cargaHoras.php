<?php

//pantalla de carga de horas (solo accesible por desarrolladores)

unset($error);

session_start();
$semanaActual = array(
    26 => 2,
    27 => 0,
    28 => 1,
    29 => 2,
    30 => 2,
    31 => 0,
    32 => 1,
    33 => 2,
    34 => 2,
    35 => 2,
    36 => 0,
    37 => 1,
    38 => 2,
    39 => 2
    );

include_once 'includes/functions.php';
sessionTimeOut();
sessioncheck(1);

include_once 'includes/db.php';
$semana = date('W');

$mostrarTabla = false;
$requete = $pdo->query('SELECT * FROM usuarios');
$users = $requete->fetchAll();
$req = 'SELECT proyectos.proyecto, proyectos.id_proyecto, cargahoras.horas, cargahoras.id_cargahoras
                            FROM cargahoras
                            LEFT JOIN proyectos ON proyectos.id_proyecto = cargahoras.id_proyecto
                            LEFT JOIN semanas ON semanas.id_semana = cargahoras.id_semana
                            WHERE cargahoras.id_usuario = ? AND cargahoras.id_semana = ?';

if (isset($_SESSION['user']->id_usuario)) {
    $requeteProyectos = $pdo->prepare('SELECT proyectos.proyecto, proyectos.id_proyecto, usuarios.usuario FROM asignacion
                                       LEFT JOIN proyectos ON proyectos.id_proyecto = asignacion.id_proyecto
                                       LEFT JOIN usuarios ON usuarios.id_usuario = asignacion.id_usuario
                                       WHERE asignacion.id_usuario = ? AND proyectos.inactivo IS NULL');
    $requeteProyectos->execute([$_SESSION['user']->id_usuario]);
    $proyectosDisponibles = $requeteProyectos->fetchAll();
    if (empty($proyectosDisponibles)) {
        $_SESSION['usuarioSinAsignacion'] = "No asignado";
        header('Location: cargaHoras.php');
        exit();
    }
}

if (isset($_SESSION['user']->id_usuario,$_GET['semana'])) {
    $mostrarTabla = true;
    $requete = $pdo->prepare($req);
    $requete->execute([$_SESSION['user']->id_usuario,$_GET['semana']]);
    $requeteHorasTotal = $pdo->prepare('SELECT sum(cargahoras.horas) as horas FROM cargahoras WHERE cargahoras.id_usuario = ? AND cargahoras.id_semana = ?');
    $requeteHorasTotal->execute([$_SESSION['user']->id_usuario,$_GET['semana']]);
    $cantHoras = $requeteHorasTotal->fetch();
    $cantEsfuerzo = $cantHoras->horas * 100 / 40;

    $tabla = $requete->fetchAll();
    $queryCantDisponible = $pdo->prepare('SELECT semanas.horas_habiles FROM semanas WHERE id_semana = ?');
    $queryCantDisponible->execute([$_GET['semana']]);
    $horasDisponibles = $queryCantDisponible->fetch();

	if($cantHoras->horas >= 40){
	$error = "disabled";
	}
	
}

if (!empty($_SESSION['user']->id_usuario) AND !empty($_GET['proyecto']) AND !empty($_GET['semana'])AND !empty($_GET['horas']) AND !empty($_GET['carga'])){
    $mostrarTabla = true;
    // carga * 40 / 100 e insertarlo
    $carga = $_GET['horas'];
    $carga = $carga * 40 / 100;
    $requeteHorasTotal = $pdo->prepare('SELECT sum(cargahoras.horas) as horas FROM cargahoras WHERE cargahoras.id_usuario = ? AND cargahoras.id_semana = ?');
    $requeteHorasTotal->execute([$_SESSION['user']->id_usuario,$_GET['semana']]);
    $cantHoras = $requeteHorasTotal->fetch();

    if ($carga > (40 - $cantHoras->horas)) {
        $proyect = $_GET['proyecto'];
        $week = $_GET['semana'];
        $_SESSION['alertaCargaMAL'] = "Ojo con lo que se pone FORROOO";
        header("Location: cargaHoras.php?proyecto=".$proyect."&semana=".$week);
        exit();
        
    }
    $requeteInsert = $pdo->prepare('INSERT INTO cargahoras SET id_proyecto = ?, id_usuario = ?, id_semana = ?, horas = ? ');
    $requeteInsert->execute([$_GET['proyecto'],$_SESSION['user']->id_usuario,$_GET['semana'],$carga]);
    $requete = $pdo->prepare($req);
    $requete->execute([$_SESSION['user']->id_usuario,$_GET['semana']]);
    $tabla = $requete->fetchAll();
    $requeteHorasTotal = $pdo->prepare('SELECT sum(cargahoras.horas) as horas FROM cargahoras WHERE cargahoras.id_usuario = ? AND cargahoras.id_semana = ?');
    $requeteHorasTotal->execute([$_SESSION['user']->id_usuario,$_GET['semana']]);
    $cantHoras = $requeteHorasTotal->fetch();
	
	if($cantHoras->horas >= 40){
	$error = "disabled";
	}
	
	}

include_once 'includes/header.php'
?>
<div class="container">
    <div class="row">
        <div class="col-md-10">
            <form class="form-horizontal" method="get">
                <fieldset>
                    <legend>Carga de esfuerzo</legend>
                    <?php if (isset($_SESSION['user']->id_usuario)):?>
                        <div class="form-group">
                            <label for="semana" class="col-lg-2 control-label">Semana</label>
                            <div class="col-lg-10">
                                <select name="semana" id="semana" <?php if (isset($_GET['semana'])) {echo "disabled"; } ?>>
                                    <?php if ($semanaActual[$semana] == 0) :?> <!-- Lógica de definición de 1ra semana del mes -->
                                        <option value="<?=$semana;?>"<?php if(isset($_GET['semana']) AND $_GET['semana'] == $semana) {echo ' selected'; } ?>>S<?=$semana;?></option>
                                    <?php elseif ($semanaActual[$semana] == 1 ) :?>
                                        <option value="<?=$semana;?>"<?php if(isset($_GET['semana']) AND $_GET['semana'] == $semana) {echo ' selected'; } ?>>S<?=$semana;?></option>
                                        <option value="<?=$semana-1;?>"<?php if(isset($_GET['semana']) AND $_GET['semana'] == $semana-1) {echo ' selected'; } ?>>S<?=$semana-1;?></option>
                                    <?php else :?>
                                        <option value="<?=$semana;?>" <?php if(isset($_GET['semana']) AND $_GET['semana'] == $semana) {echo ' selected'; } ?>>S<?=$semana;?></option>
                                        <option value="<?=$semana-1;?>" <?php if(isset($_GET['semana']) AND $_GET['semana'] == $semana-1) {echo ' selected'; } ?>>S<?=$semana-1;?></option>
                                        <option value="<?=$semana-2;?>" <?php if(isset($_GET['semana']) AND $_GET['semana'] == $semana-2) {echo ' selected'; } ?>>S<?=$semana-2;?></option>
                                    <?php endif;?>
                                </select>
                                <?php if (isset($_GET['semana'])) :?>
                                    <a href="cargaHoras.php">Cambiar semana</a>
                                <?php endif;?>
                            </div>
                        </div>
                    <?php endif;?>
                    <?php if (isset($_SESSION['user']->id_usuario,$_GET['semana'])) :?>
                        <div class="form-group">
                            <label for="proyecto" class="col-lg-2 control-label">Proyecto</label>
                            <div class="col-lg-10">
                                <select name="proyecto" id="proyecto" <?php if (isset($_GET['proyecto'])) {echo 'disabled'; }?> >
                                    <?php foreach($proyectosDisponibles as $key => $value):?>
                                        <option value="<?php echo $value->id_proyecto;?>" <?php if(!empty($_SESSION['user']->id_usuario) AND !empty($_GET['proyecto']) AND $_GET['proyecto'] == $value->id_proyecto) { echo 'selected';}?> ><?php echo $value->proyecto?></option>
                                    <?php endforeach;?>
                                </select>
                                <?php if (isset($_GET['proyecto'])) :?>
                                    <a href="cargaHoras.php?semana=<?=$_GET['semana'];?>">Cambiar proyecto</a>
                                <?php endif;?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($_GET['proyecto'])):?>
                        <div class="form-group">
                            <label for="horas" class="col-lg-2 control-label">% Esfuerzo</label>
                            <div class="col-lg-8">
                                <?php if ($cantHoras->horas < 40) :?> <!-- registro de esfuerzo -->
                                    <select name="horas" <?php if (isset($_GET['horas'])) :?> disabled <?php endif;?>>
                                        <?php for ($i = 1 ; $i <= 20 ; $i ++) :?> <!-- relleno de desplegable de carga -->
                                            <option value="<?=$i*5;?>"><?=$i*5;?></option>
                                        <?php endfor;?>
                                    </select>
                                    <br>
                                    <h5><label class="label label-info">% de esfuerzo restante <?= 100 - ($cantHoras->horas * 100 / 40);?></label></h5>
                                        <?php if (isset($_SESSION['alertaCargaMAL'])): ?> <!-- alerta por carga superior -->
                                            <label class="label label-danger">Se ha seleccionado un carga superior a lo permitido</label>
                                            <?php unset($_SESSION['alertaCargaMAL']);?>
                                        <?php endif;?>
                                    <?php if (isset($_GET['carga'])) :?>
                                        <a href="cargaHoras.php?semana=<?=$_GET['semana']?>">Nueva carga</a>
                                    <?php endif;?>
                                <?php else : ?>
                                    <h5><label class="label label-danger">No es posible cargar horas debido a que se ha alcanzado el maximo disponible</label></h5>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (!isset($_SESSION['user']->id_usuario,$_GET['proyecto'],$_GET['semana'],$_GET['horas'],$_GET['carga'])):?>
                    <div class="form-group">
                        <div class="col-lg-10 col-lg-offset-2"> <!-- lógica para mostrar/ocultar/deshabilitar botones -->
                            <?php if (isset($_SESSION['user']->id_usuario,$_GET['semana'])) :?>
                                <input type="hidden" name="semana" value="<?=$_GET['semana'];?>">
                            <?php endif;?>
                            <?php if (isset($_GET['proyecto'])) :?>
                                <input type="hidden" name="proyecto" value="<?=$_GET['proyecto'];?>">
                            <?php endif;?>
                                <a href="cargaHoras.php" class="btn btn-default">Cancel</a>
                            <?php if ((isset($_GET['semana']) AND isset($error)) OR (isset($_GET['semana']) AND isset($_GET['proyecto']) AND isset($error))): ?>
                                <button type="submit" class="btn btn-primary" disabled="">Siguiente</button>
                            <?php elseif (isset($_GET['proyecto']) AND isset($_GET['semana']) AND !isset($error)): ?> 
                                <button type="submit" class="btn btn-primary" name="carga" value="ok">Siguiente</button>
                            <?php elseif (isset($_GET['proyecto']) AND isset($_GET['semana'])) :?>
                                <button type="submit" class="btn btn-primary">Siguiente</button>
                            <?php else :?>
                                <button type="submit" class="btn btn-primary">Siguiente</button>
                            <?php endif;?>
                        </div>
                    </div>
                    <?php endif;?>
                </fieldset>
            </form>
            <?php if ($mostrarTabla === true AND $cantHoras->horas > 0) :?>
                <?php require 'tablaResultado.php'; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'?>