<div>
	<!-- tabla resumen de carga registrada -->
    <h2>Esfuerzo total <?=$cantHoras->horas * 100 / 40;?>%</h2>
    <?php if ($cantHoras->horas > 40) :?>
        <div class="alert alert-dismissible alert-danger">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Sobrecarga de esfuerzo en el proyecto</strong>
        </div>
    <?php endif;?>
    <div class="row">
        <div class="col-md-8">
            <table class="table table-striped table-hover ">
                <thead>
                <tr>
                    <th>Proyecto</th>
                    <th>Esfuerzo</th>
                    <th></th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Proyecto</th>
                    <th>Esfuerzo</th>
                    <th></th>
                </tr>
                </tfoot>
                <tbody>
                <?php for ($i = 0 ; $i < count($tabla) ; $i++): ?>
                    <tr <?php if (isset($_GET['lineaModif']) AND $_GET['lineaModif'] === $tabla[$i]->id_cargahoras ) { echo 'class="warning"';}?>>
                        <td><?=$tabla[$i]->proyecto; ?></td>
                        <td><?=$tabla[$i]->horas * 100 / 40;?> %</td>
                        <td><a href="deleteTask.php?linea=<?=$tabla[$i]->id_cargahoras?><?php if (isset($_GET['proyecto'])) { echo "&amp;proyecto=".$tabla[$i]->id_proyecto;}?>&amp;usuario=<?=$_SESSION['user']->id_usuario;?>&amp;semana=<?=$_GET['semana'];?>" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span></a></td>
                    </tr>
                <?php endfor;?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div>

</div>