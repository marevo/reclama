<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 29.08.2017
 * Time: 14:40
 */
require '../../autoload.php';
if(isset($_POST['submitOneSupplier'])){
//    echo "пришли данные на обновление" ;
    if(isset($_POST['id'])){
        $idSupplier = intval($_POST['id']);
        $supp = \App\Models\Supplier::findObjByIdStatic($idSupplier);
        if(false != $supp){
            if(isset($_POST['name'])){
                $supp->name = trim(htmlspecialchars($_POST['name']));
            }
            if(isset($_POST['addCharacteristic'])){
                $supp->addCharacteristic = trim(htmlspecialchars($_POST['addCharacteristic']));
            }
            if(isset($_POST['contactPerson'])){
                $supp->contactPerson = trim(htmlspecialchars($_POST['contactPerson']));
            }
            if(isset($_POST['phone0'])){
                $supp->phone0 = trim(htmlspecialchars($_POST['phone0']));
            }
            if(isset($_POST['email0'])){
                $supp->email0 = htmlspecialchars($_POST['email0']);
            }
            if(isset($_POST['address'])){
                $supp->address = trim(htmlspecialchars($_POST['address']));
            }
            if(isset($_POST['deliveryDay'])){
                $supp->deliveryDay = intval($_POST['deliveryDay']);
            }
            $res = $supp->update();
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