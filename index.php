<?php

//Login + pantalla inicial

session_start();
include_once 'includes/functions.php';
sessionTimeOut();
require_once 'includes/db.php';

$reqUsers = $pdo->query("SELECT * FROM usuarios");
$usuariosParaLogin = $reqUsers->fetchAll();

//Login

if (isset($_POST['submit']) AND !empty($_POST['submit'])) {
    if (!empty($_POST['user']) AND !empty($_POST['password'])) {
        $user = $_POST['user']; $pass = $_POST['password'];
        $req = $pdo->prepare('SELECT usuarios.usuario, usuarios.password, usuarios.email, usuarios.user_rol, usuarios.id_usuario FROM usuarios WHERE usuarios.email = :username OR usuarios.usuario = :username');
        $req->execute(['username' => $user]);
        if ($req->rowCount() == 0) {
            $_SESSION['userPass'] = "Usuario inexistente";
        }
        else {
            $datosUser = $req->fetch();
            if ($pass === $datosUser->password) {
                $_SESSION['user'] = $datosUser;
            }
            else {
                $_SESSION['userPass'] = "Ingresar usuario y password validos";
            }
        }    }
    else {
        $_SESSION['userPass'] = "Ingresar usuario y password validos";
    }
}

require_once 'includes/header.php';
?>

<div class="container">
    <?php if (isset($_SESSION['noAuth'])) { echo $_SESSION['noAuth']; unset($_SESSION['noAuth']); } ?>
    
    <?php if (!isset($_SESSION['user'])) :?>
        <div class="row">
            <div class="col-md-6">
                <form class="form-horizontal" method="post">
                    <fieldset>
                        <legend>Login de usuario</legend>
                        <?php if (isset($_SESSION['userPass'])) { echo '<label class="label label-danger">'.$_SESSION['userPass'].'</label>';}unset($_SESSION['userPass']);?>
                        <div class="form-group">
                            <label for="inputUser" class="col-lg-2 control-label">Email</label>
                            <div class="col-lg-10">
                                <input class="form-control" id="inputUser" placeholder="Usuario" type="text" name="user">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword" class="col-lg-2 control-label">Password</label>
                            <div class="col-lg-10">
                                <input class="form-control" id="inputPassword" placeholder="Password" type="password" name="password">
                                <div class="checkbox"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-10 col-lg-offset-2">
                                <button type="reset" class="btn btn-default">Cancel</button>
                                <button type="submit" class="btn btn-primary" name="submit" value="ok">Submit</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
                <table class="table table-striped hidden">
                    <thead>
                        <th>Id</th>
                        <th>Usuario</th>
                        <th>Pass</th>
                    </thead>
                    <tfoot>
                        <th>Id</th>
                        <th>Usuario</th>
                        <th>Pass</th>
                    </tfoot>
                    <tbody>
                        <?php foreach ($usuariosParaLogin as $key => $item) :?>
                            <tr>
                        <td><?=$key;?></td>
                        <td><?=$item->usuario;?></td>
                        <td><?=$item->password;?></td>
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php else :?>
        <div>
            <div class="jumbotron">
                <h3>Bienvenido</h3>
                <p>Por favor seleccionar del menu lo que se desee hacer</p>
                <?php if ($_SESSION['user']->user_rol < 5) : ?>
                <a class="btn btn-lg btn-default" href="cargaHoras.php"><span class="glyphicon glyphicon-hand-right"></span>  Ir a cargar esfuerzo <span class="glyphicon glyphicon-briefcase"></span></a>
                <?php endif; ?>
                <a class="btn btn-lg btn-info" href="logout.php">Desloguease? <span class="glyphicon glyphicon-off"></span></a>
            </div>
        </div>
    <?php endif;?>
</div>
<?php require_once 'includes/footer.php';?>