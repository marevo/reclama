<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 05.09.2017
 * Time: 22:00
 */
require "../../autoload.php";


if(isset($_POST['deleteClientFromBase'])){
    echo "пришел запрос на удаление поставщика из базы";
    if (isset($_POST['idClient'])){
        $idClient = intval($_POST['idClient']);
//        $res = true;
//        $res = false;
        $res = \App\Models\Client::deleteObj($idMat);
        if($res){
            \App\ModelLikeTable::showUspeh('успешно удалили');
            echo "<script>$('[data-id = $idClient]').parent().remove() ;</script>";
        }
        else{
            \App\ModelLikeTable::showNoUspeh('не удалось удалить клиента');
        }
    }
}
//поиск клиентов  по подобию в name или 
if(isset($_POST['searchLike'])){
    if(isset($_POST['likeValue'])){
        $likeValue = htmlspecialchars($_POST['likeValue']);
        $findClients = \App\Models\Client::searchAllForLikeNameOrLikeContactPerson($likeValue);
//        var_dump($findClients);
        if(! empty ($findClients)){
            $tableAllClientTbody = "";
            foreach ($findClients as $item){
                //найдем заказы для каждого клиента, чтобы узнать есть или нет у него заказы и
                // разрешать удалять только тех клиентов, у которых нет  заказов
                if($item->ifExistAnyOrderForThisClient()){
//                                есть  заказы поэтому не будем разрешать удалять клента
                    $tableAllClients .= "<tr><td class='tdDisplayNone'>$item->id</td><td>$item->name</td><td>$item->contactPerson</td>" .
                        "<td>$item->phone0</td><td>$item->phone1</td><td>$item->email0</td><td>$item->address</td>" .
                        "<td class='text-center'><a href='viewOneClient.php?id=$item->id'><span class='glyphicon glyphicon-eye-open'></span></a></td><td></td></tr>";
                }
                else{
                    // нет заказов у этого клиента, поэтому разрешим его удаление
                    $tableAllClients .= "<tr><td class='tdDisplayNone'>$item->id</td><td>$item->name</td><td>$item->contactPerson</td>" .
                        "<td>$item->phone0</td><td>$item->phone1</td><td>$item->email0</td><td>$item->address</td>" .
                        "<td class='text-center'><a href='viewOneClient.php?id=$item->id'><span class='glyphicon glyphicon-eye-open'></span></a></td>" .
                        "<td data-id='$item->id' class='text-center'><span class='glyphicon glyphicon-trash'></span></td></tr>";
                }
            }
            \App\ModelLikeTable::showUspeh('есть такие клиенты');
        }
        else{
            $tableAllClients = "пока ничего нет (";
            \App\ModelLikeTable::showNoUspeh('нет клиентов с такими данными');
        }
        echo "$tableAllClients";
    }
}
