<?php

//pantalla de creacion de proyectos (tipo desarrollo y capacitacion externa), creacion de usuarios (tipo desarrollador) y asignacion de usuarios a proyectos (solo desarrollo y capacitacion externa)

session_start();
include_once 'includes/functions.php';
sessioncheck(8);
sessionTimeOut();
require_once 'includes/db.php';

$requete = $pdo->query('SELECT * FROM proyectos 
                        LEFT JOIN tipo_proyecto ON proyectos.proyecto_id_tipo = tipo_proyecto.id_tipo 
                        WHERE proyectos.inactivo IS NULL
                        AND tipo_proyecto.id_tipo not in (1,2)');
$proyectos = $requete->fetchAll();

$reTipoProyecto = $pdo->query("SELECT * FROM tipo_proyecto WHERE tipo_proyecto.id_tipo not in (1,2)");
$tipoProyectos = $reTipoProyecto->fetchAll();

//asociacion

if (isset($_GET['proyecto'],$_GET['asociar'])) {

    $requete = $pdo->prepare('SELECT usuarios.id_usuario, usuarios.usuario FROM usuarios
                              WHERE usuarios.id_usuario NOT IN
                              (
                                  SELECT DISTINCT(asignacion.id_usuario)
                                  FROM asignacion
                                  LEFT JOIN proyectos ON proyectos.id_proyecto = asignacion.id_proyecto
                                  WHERE proyectos.id_proyecto = ?
                              )
                              AND usuarios.user_rol < 5');

    $requete->execute([$_GET['proyecto']]);
    $usuarios = $requete->fetchAll();
    if (empty($usuarios)) {
        $_SESSION['imposibleAsignar'] = "<h5><label class='label label-warning'>Error - No hay usuarios para asignar</label></h5>";
        header('Location: abmasoc.php');
        exit();
    }
   
}

if (isset($_GET['usuario'],$_GET['proyecto'],$_GET['asignacion'])) {
    $requete = $pdo->prepare('INSERT INTO asignacion SET asignacion.id_proyecto = ?, asignacion.id_usuario = ?');
  

    $requete->execute([$_GET['proyecto'],$_GET['usuario']]);
    $_SESSION['imposibleAsignar'] = "<h5><label class='label label-success'>Afectación satisfactoria</label></h5>";
    header('Location: abmasoc.php');
    exit();
}

require_once 'includes/header.php'; ?>

<div class="container">
    <div class="row">
        <h2>Administrador</h2>
        <div class="col-md-6">
            <form class="form-horizontal" method="get">
                <fieldset>
                    <legend>Asignacion de proyectos a usuarios</legend> <!-- Logica de asignacion de usuarios a PR -->
                    <?php if(isset($_SESSION['imposibleAsignar'])) {echo $_SESSION['imposibleAsignar'];}?> 
                    <input type="hidden" value="ok" name="asociar"/>
                    <div class="form-group">
                        <label for="proyecto" class="col-lg-2 control-label">Proyecto</label>
                        <div class="col-lg-10">
                            <select name="proyecto" id="proyecto" <?php if (isset($_GET['proyecto'],$_GET['asociar'])) {echo 'disabled'; }?> >
                                <?php foreach($proyectos as $key => $value):?>
                                    <option <?php if ($value->proyecto_id_tipo == 5): ?> style="color: #DA0003;" <?php endif;?> value="<?php echo $value->id_proyecto;?>" <?php if(!empty($_GET['proyecto'])AND !empty($_GET['asociar']) AND $_GET['proyecto'] == $value->id_proyecto) { echo 'selected';}?> ><?php echo $value->proyecto?></option>
                                <?php endforeach;?>
                            </select>
                            <?php if (isset($_GET['proyecto'],$_GET['asociar'])) :?>
                                <a href="abmasoc.php">Cambiar proyecto</a>
                            <?php endif;?>
                        </div>
                    </div>
                    <?php if (isset($_GET['proyecto'],$_GET['asociar'])):?>
                        <div class="form-group">
                            <label for="usuario" class="col-lg-2 control-label">Usuario</label>
                            <div class="col-lg-10">
                                <select name="usuario" id="usuario">
                                    <?php foreach($usuarios as $key => $value):?>
                                        <option value="<?php echo $value->id_usuario;?>"><?php echo $value->usuario?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                    <?php endif;?>
                    <?php if (!isset($_GET['proyecto'],$_GET['asignacion'])):?>
                        <div class="form-group">
                            <div class="col-lg-10 col-lg-offset-2">
                                <?php if (isset($_GET['proyecto'])) :?>
                                    <input type="hidden" name="proyecto" value="<?=$_GET['proyecto'];?>">
                                <?php endif;?>
                                <a href="abmasoc.php" class="btn btn-default">Cancel</a>
                                <button type="submit" class="btn btn-primary" <?php if (isset($_GET['proyecto'])) { echo 'name="asignacion" value="ok"'; }?>><?php if (isset($_GET['asociar'])) {echo "Asignar";} else { echo "Siguiente";}?></button>
                            </div>
                        </div>
                    <?php endif;?>
                </fieldset>
            </form>
        </div>
        <div class="col-md-6">
            <form class="form-horizontal" method="get" action="process/createuser.php">
                <fieldset>
                <legend>Crear usuario</legend> <!-- Logica de creacion de usuarios -->
                <?php if(isset($_SESSION['createUser'])) {echo $_SESSION['createUser'];}?>
                <div class="form-group">
                        <label for="user" class="col-lg-2 control-label">Usuario</label>
                        <div class="col-lg-10">
                            <input type="text" name="user" id="user" placeholder="Ingresar nombre de usuario">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sueldo" class="col-lg-2 control-label">Sueldo</label>
                        <div class="col-lg-10">
                            <input type="number" name="sueldo" id="sueldo" placeholder="Ingresar sueldo de usuario" min="1000" value="1000">
                        </div>
                    </div>
                <div class="form-group">
                    <div class="col-lg-10 col-lg-offset-2">
                        <button type="reset" class="btn btn-default">Limpiar</button>
                        <button type="submit" class="btn btn-success" >Crear</button>
                    </div>
                </div>
                </fieldset>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <form class="form-horizontal" method="get" action="process/createProyect.php">
                <fieldset>
                <legend>Crear Proyecto</legend>  <!-- Logica de creacion de proyectos -->
                <?php if(isset($_SESSION['createProyect'])) {echo $_SESSION['createProyect'];}?>
                    <div class="form-group">
                        <label for="proyect" class="col-lg-2 control-label">Proyecto</label>
                        <div class="col-lg-10">
                            <input type="text" name="proyect" id="proyect" placeholder="Ingresar nombre del proyecto">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tipoproyecto" class="col-lg-2 control-label">Tipo</label>
                        <div class="col-lg-10">
                            <select name="tipoproyecto" id="tipoproyecto">
                                <?php foreach($tipoProyectos as $key => $value):?>
                                    <option value="<?php echo $value->id_tipo;?>"><?php echo $value->tipo_description?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-10 col-lg-offset-2">
                            <button type="reset" class="btn btn-default">Limpiar</button>
                            <button type="submit" class="btn btn-success" >Crear</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; unset($_SESSION['createProyect'],$_SESSION['createUser'],$_SESSION['imposibleAsignar'])?>
