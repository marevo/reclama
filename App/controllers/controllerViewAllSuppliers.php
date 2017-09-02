<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 30.08.2017
 * Time: 21:54
 */
require '../../autoload.php';

if(isset($_POST['deleteSupplierFromBase'])){
    echo "пришел запрос на удаление поставщика из базы";
    if (isset($_POST['idMaterial'])){
        $idSupp = intval($_POST['idSupplier']);
//        $res = true;
//        $res = false;
        $res = \App\Models\Supplier::deleteObj($idMat);
        if($res){
            echo "<script>fUspehAll('удалили успешно')</script>";
            echo "<script>$('[data-id_supplier = $idSupp]').parent().remove() ;</script>";
        }
        else{
            echo "<script>fNoUspehAll('не удалось удалить материал')</script>";
        }
    }
}