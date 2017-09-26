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
            if(isset($_POST['site'])){
                $supp->site = trim(htmlspecialchars($_POST['site']));
            }
            $res = $supp->update();
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

if(isset($_POST['addNewSupplier'])){
//    echo "пришел запрос на добавление в базу поставщика";
//    die();
    $suppNew = new \App\Models\Supplier();
    
    if(isset($_POST['nameSupplier'])){
        $suppNew->name = htmlspecialchars($_POST['nameSupplier']);
    }
    if(isset($_POST['addCharacteristic'])){
        $suppNew->addCharacteristic = htmlspecialchars($_POST['addCharacteristic']);
    }
    if(isset($_POST['contactPerson'])){
        $suppNew->contactPerson = trim(htmlspecialchars($_POST['contactPerson']));
    }
    if(isset($_POST['phone0'])){
        $suppNew->phone0 = trim(htmlspecialchars($_POST['phone0']));
    }
    if(isset($_POST['email0'])){
        $suppNew->email0 = htmlspecialchars($_POST['email0']);
    }
    if(isset($_POST['address'])){
        $suppNew->address = trim(htmlspecialchars($_POST['address']));
    }
    if(isset($_POST['deliveryDay'])){
        $suppNew->deliveryDay = intval($_POST['deliveryDay']);
    }
    if(isset($_POST['site'])){
        $suppNew->site = trim(htmlspecialchars($_POST['site']));
    }
    $res = $suppNew->insert();
    //вставка в базу прошло удачно
//    $res = false;
//    $res = true;
    if(false != $res){
        echo "<script>fUspehAll('добавили успешно нового поставщика');</script>";
//        echo "<script>setTimeout(function() {location.reload()},2000); </script>";
    }
    else{
        echo "<script>fNoUspehAll('не удалось добавить нового поставщика (');</script>";
    }

}

if(isset($_POST['isExistNameSupplier'])){
//    echo "пришел запрос на проверку уникальности имени ". $_POST['nameSupplier'];
//    \App\ModelLikeTable::showUspeh('запрос на проверку уникальности имени поставщика '.$_POST['nameSupplier']);
//    die();
    if(isset($_POST['nameSupplier'])){
        $nameS = htmlspecialchars( $_POST['nameSupplier'] ) ;
        if(\App\Models\Supplier::ifExistObjWithName($nameS)){
            echo "<script> $('[name=nameSupplier').before('<div class=\'alert alert-info\'>$nameS -  такое имя есть ! выберите другое имя :(  </div>');$('[name=nameSupplier').focus(); </script>";
            \App\ModelLikeTable::showUspeh(" имя $nameS не уникально измените ! ");

        }else{
            \App\ModelLikeTable::showUspeh("$nameS имя уникально ");
        }
    }
}
if(isset($_POST['isExistPhone0Supplier'])){
    if(isset($_POST['phone0Supplier'])){
        $phone0Sup = htmlspecialchars( $_POST['phone0Supplier'] );
//        \App\ModelLikeTable::showUspeh("пришел запрос на проверку уникальности isExistPhone0Supplier $phone0Sup");
        if(\App\Models\Supplier::ifExistObjWithPhone0($phone0Sup)){
            echo "<script> $('[name=phone0]').before('<div class=\'alert alert-info\'> $phone0Sup такой телефон есть ! выберите другой :(</div>');$('[name=phone0]').focus();</script>";
            \App\ModelLikeTable::showNoUspeh(" $phone0Sup номер не уникален измените номер");

        }else{
            \App\ModelLikeTable::showUspeh(" $phone0Sup уникален");
        }

    }
}
