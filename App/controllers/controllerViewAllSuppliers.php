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
//поиск поставщиков по подобию в названии или доп характеристик
if(isset($_POST['searchLike'])){
    if(isset($_POST['likeValue'])){
        $likeValue = htmlspecialchars($_POST['likeValue']);
        $findSuppliers = \App\Models\Supplier::searchObjectsLikeNameOrLikeAddCharacteristic($likeValue);
//        var_dump($findSuppliers);
        if(! empty ($findSuppliers)){
            $tableAllSuppTbody = "";
            foreach ($findSuppliers as $item){
                //найдем idMaterial для каждого поставщика, чтобы узнать есть или нет эти материалы в заказах и
                // разрешать удалять только тех поставщиков, чьих материалов нет в заказах
                if(\App\Models\MaterialsToOrder::ifExistThisSupplierInAnyMaterilsToOrder($item->id)){
//                                есть материал этото поставщика хотябы в одном заказе поэтому не будем разрешать удалять поставщика
                    $tableAllSuppTbody .= "<tr><td class='tdDisplayNone'>$item->id</td><td>$item->name</td><td>$item->addCharacteristic</td><td class='tdDisplayNone'>$item->contactPerson</td><td>$item->phone0</td><td class='tdDisplayNone'>$item->email0</td><td>$item->address</td><td class='tdDisplayNone'> ".$item->getDeliveryDays()." </td><td class='tdDisplayNone'><a href='$item->site' target='_blank'>$item->site</a></td><td class='text-center'><a href='viewOneSupplier.php?id=$item->id'><span class='glyphicon glyphicon-eye-open'></span></a></td><td></td></tr>";
                }
                else{
                    // нет материалов этого поставщика, поэтому разрешим его удаление
                    $tableAllSuppTbody .= "<tr><td class='tdDisplayNone'>$item->id</td><td>$item->name</td><td>$item->addCharacteristic</td><td class='tdDisplayNone'>$item->contactPerson</td><td>$item->phone0</td><td class='tdDisplayNone'>$item->email0</td><td>$item->address</td><td class='tdDisplayNone'> ".$item->getDeliveryDays()." </td><td class='tdDisplayNone'><a href='$item->site' target='_blank'>$item->site</a></td><td class='text-center'><a href='viewOneSupplier.php?id=$item->id'><span class='glyphicon glyphicon-eye-open'></span></a></td><td data-id_supplier='$item->id' class='text-center'><span class='glyphicon glyphicon-trash'></span></td></tr>";
                }
            }
//            $tableAllSuppTbody .= "";
        }
        else{
            $tableAllSuppTbody = "пока ничего нет (";
        }
        echo "$tableAllSuppTbody";
    }
}
