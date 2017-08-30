<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 30.08.2017
 * Time: 21:54
 */
require '../../autoload.php';

if(isset($_POST['deleteMaterialFromBase'])){
    echo "пришел запрос на удаление материала из базы";
    if (isset($_POST['idMaterial'])){
        $idMat = intval($_POST['idMaterial']);
//        $res = true;
//        $res = false;
        $res = \App\Models\Material::deleteObj($idMat);
        if($res){
            echo "<script>fUspehAll('удалили успешно')</script>";
            echo "<script>$('[data-id = $idMat]').parent().remove() ;</script>";
        }
        else{
            echo "<script>fNoUspehAll('не удалось удалить материал')</script>";
        }
    }
}