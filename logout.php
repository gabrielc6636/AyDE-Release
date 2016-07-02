<?php
/**
 * Created by PhpStorm.
 * User: U411207
 * Date: 20/05/2016
 * Time: 13:44
 */
session_start();
session_destroy();
header('Location: index.php');
exit();