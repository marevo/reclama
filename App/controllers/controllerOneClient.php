<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 06.09.2017
 * Time: 14:33
 */

require '../../autoload.php';

if(isset($_POST['submitUpdateClient'])){
//    echo "пришли данные на обновление клиента" ;
    if(isset($_POST['id'])){
        $idClientlient = intval($_POST['id']);
        $clientUpdate = \App\Models\Client::findObjByIdStatic($idClientlient);
        if(false != $clientUpdate){
            if(isset($_POST['name'])){
                $clientUpdate->name = htmlspecialchars($_POST['name']);
            }
            if(isset($_POST['contactPerson'])){
                $clientUpdate->contactPerson = htmlspecialchars($_POST['contactPerson']);
            }
            if(isset($_POST['phone0'])){
                $phone0 = $_POST['phone0'];
                if($phone0=='')
                    $phone0 = 'null';
                $clientUpdate->phone0 = htmlspecialchars($phone0);
            }
            if(isset($_POST['phone1'])){
                $clientUpdate->phone1 = htmlspecialchars($_POST['phone1']);
            }
            if(isset($_POST['email0'])){
                $clientUpdate->email0 = htmlspecialchars($_POST['email0']);
            }
            if(isset($_POST['address'])){
                $clientUpdate->address = htmlspecialchars($_POST['address']);
            }
            $res = $clientUpdate->update();
            //обновление прошло удачно
            if(false != $res){
                echo "<script>fUspehAll('обновили успешно');</script>";
                echo "<script>setTimeout(function() {location.reload()},2000); </script>";
            }
            else{
                echo "<script>fNoUspehAll('не удалось обновить (')</script>";
            }
        }
    }
}

