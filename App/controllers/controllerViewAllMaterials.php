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

//поиск материалов по подобию в названии или доп характеристик
if(isset($_POST['searchLike'])){
    if(isset($_POST['likeValue'])){
        $likeValue = htmlspecialchars($_POST['likeValue']);
        $findMat = \App\Models\Material::selectForViewLikeNameOrLikeAddCharacteristic($likeValue);
        var_dump($findMat);

        if(! empty ($findMat)){
            $tableSearchMatTbody = "";
            foreach ($findMat as $item){
//                                получим не false если есть этот материал хотябы в одном заказе
                $ifExistOrderWithIdMaterial = \App\Models\MaterialsToOrder::ifExistThisMaterialInAnyOneOrder($item[id]);
//                                if($ifExistOrderWithIdMaterial )
//                                   echo "<br/> c idMaterials = $item[id] есть заказы )";
//                                else
//                                    echo "<br/>   c idMaterials = $item[id] нет  заказов ";

                if($ifExistOrderWithIdMaterial){
                    $tableSearchMatTbody .= "<tr><td style='display: none;'>$item[id]</td><td>$item[name]</td><td>$item[addCharacteristic]</td><td>$item[measure]</td><td>$item[deliveryForm]</td><td>$item[priceForMeasure]</td><td style='display: none;'>$item[idSupplier]</td><td>$item[nameSupplier]</td><td><a href='viewOneMaterial.php?id=$item[id]'><span class='glyphicon glyphicon-eye-open'></span></a></td><td></td></tr>";
                }
                else{
                    //получили false на запрос значит в заказах не используется это материал вствавим иконку удаления
                    $tableSearchMatTbody .= "<tr><td style='display: none;'>$item[id]</td><td>$item[name]</td><td>$item[addCharacteristic]</td><td>$item[measure]</td><td>$item[deliveryForm]</td><td>$item[priceForMeasure]</td><td style='display: none;'>$item[idSupplier]</td><td>$item[nameSupplier]</td><td><a href='viewOneMaterial.php?id=$item[id]'><span class='glyphicon glyphicon-eye-open'></span></a></td><td data-id='$item[id]'><span class=\"glyphicon glyphicon-trash\"></span></td></tr>";
                }
            }
//            $tableSearchMatTbody .= "";
        }
        else{
            $tableSearchMatTbody = "пока ничего нет (";
        }
        echo "$tableSearchMatTbody";
    }
}