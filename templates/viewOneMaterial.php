<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 27.08.2017
 * Time: 23:17
 */
require '../autoload.php';
$IDORDER = 0;
if(isset($_GET['id'])){
//    если передали id значит работаем с ним иначе будем брать в else по умолчанию id=1
    $idMaterial = $_GET['id'];
}
else{
    $idMaterial = 1;
}


