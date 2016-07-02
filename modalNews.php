<?php
/**
 * Created by PhpStorm.
 * User: U411207
 * Date: 20/05/2016
 * Time: 7:57
 */
?>

<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><u>Novedades de la aplicacion</u></h4>
      </div>
      <div class="modal-body">
          <h5>Version de la aplicacion <kbd>v2.2</kbd></h5>
          <h5>27/Mayo <span class="glyphicon glyphicon-star"></span></h5>
          <ul>
              <li>Se agregó la funcion de check de session sino se destruye.</li>
          </ul>
          <h5>21/Mayo</h5>
          <ul>
              <li>Modificacion de funcion de rol</li>
              <li>Creacion de usuario admin/root</li>
              <li>Modificacion de la barra de navegación según acceso y rol</li>
              <li>Modificacion de mensaje de bienvenida <a href="#" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-hand-right"></span>  Ir a cargar horas <span class="glyphicon glyphicon-briefcase"></span></a></li>
              <li>Modificacion de la disposición de las tablas de <a href="listarProyectos.php">Listar proyectos</a></li>
          </ul>
          <h5>20/Mayo</h5>
          <ul>
              <li>Funcionalidad de boton modificar con Modal</li>
              <li>Al modificar la linea se modifica la clase dando la impresion de modificada</li>
              <li>Creacion de INDEX con botones por Logueo</li>
              <li>Se agregó la verificación del rol</li>
          </ul>
          <h5>19/Mayo</h5>
          <ul>
              <li>Se agregó el modal de novedades </li>
              <li>Se adieron 2 columnas dentro de index (modificar carga y eliminar carga)</li>
              <li>Se agregó la funcionalidad de eliminar tarea</li>
              <li>No funcional aún (modificar carga)</li>
          </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>