<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 28.08.2017
 * Time: 17:12
 */
require '../../autoload.php';
if(isset($_POST['submitOneMaterial'])){
//    echo "пришли данные на обновление" ;
    if(isset($_POST['id'])){
        $idMaterial = intval($_POST['id']);
        $mat = \App\Models\Material::findObjByIdStatic($idMaterial);
        if(false != $mat){
            if(isset($_POST['name'])){
                $mat->name = trim(htmlspecialchars($_POST['name']));
            }
            if(isset($_POST['addCharacteristic'])){
                $mat->addCharacteristic = trim(htmlspecialchars($_POST['addCharacteristic']));
            }
            if(isset($_POST['measure'])){
                $mat->measure = trim(htmlspecialchars($_POST['measure']));
            }
            if(isset($_POST['deliveryForm'])){
                $mat->deliveryForm = trim(htmlspecialchars($_POST['deliveryForm']));
            }
            if(isset($_POST['priceForMeasure'])){
                $mat->priceForMeasure = htmlspecialchars($_POST['priceForMeasure']);
            }
            if(isset($_POST['id_suppliers'])){
                $mat->id_suppliers = intval($_POST['id_suppliers']);
            }
            $res = $mat->update();
            //обновление прошло удачно
            if(false != $res){
                echo "<script>fUspehAll('обновили успешно');</script>";
                echo "<script>setTimeout(function() {location.reload()},2000); </script>";
            }
            else{
                echo "<script>fNoUspeh('не удалось обновить (')</script>";
            }
        }
    }
    


}