<?php
require '../../autoload.php';


//if (isset($_POST['submitFromFormOneMaterial'])){
if (isset($_POST['sendClientToAddToBase'])){
    insertNewClientToBase();
}
//функция вставки в базу нового клиента
function insertNewClientToBase(){
    if (isset($_POST['sendClientToAddToBase'])) {
        $clNew = new \App\Models\Client();
        if (isset($_POST['nameClient'])) {
            $clNew->name =  htmlspecialchars($_POST['nameClient']);
        }
        if (isset($_POST['contactPerson'])) {
            $clNew->contactPerson = trim( htmlspecialchars($_POST['contactPerson']));
        }
        if (isset($_POST['phone0'])) {
            $clNew -> phone0 = htmlspecialchars($_POST['phone0']);
        }
        if (isset($_POST['phone1'])) {
            $clNew -> phone1 = htmlspecialchars($_POST['phone1']);
        }
        if(isset($_POST['email0'])){
            $clNew -> email0 = trim(htmlspecialchars($_POST['email0']));
        }
        if(isset($_POST['address'])){
            $clNew->address = htmlspecialchars($_POST['address']);
        }
        //вставим новый заказ в базу
        $resInsert = $clNew -> insert();

        if($resInsert != false){
           \App\ModelLikeTable::showUspeh('клиент усешно добавлен');
        }
        else{
           \App\ModelLikeTable::showNoUspeh('не удалось добавить клиента');
        }
    }
}
//проверка на уникольность имени клиента не может быть двух одинаковых имен
if(isset($_POST['testOnUniqNameClient'])){
    if(isset($_POST['nameClient'])){
//        echo "пришел запрос на проверку имени клиента на уникальность";
        $nameNewCl = htmlspecialchars($_POST['nameClient']);
//        die($nameNewCl);

        $findClientWithNameNewClient = \App\Models\Client::ifExistObjWithName($nameNewCl);
        if(false == $findClientWithNameNewClient ){
            //клиента с таким именем нету в базе клиентов
            \App\ModelLikeTable::showUspeh('имя не повторяется в базе');
        }else{
            \App\ModelLikeTable::showNoUspeh('такое имя уже есть !!!!');
            echo "<script>$('#idNameClient').before('<div class=\'alert alert-info\'>такое имя клиента уже есть - поменияйте</div>');</script>";
        }

    }
}


