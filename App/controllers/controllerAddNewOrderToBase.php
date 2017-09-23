<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 08.09.2017
 * Time: 22:02
 */
require '../../autoload.php';
if (isset($_POST['controlka'])) {
    \App\ModelLikeTable::showUspeh('запрос на добавление нового заказа');

    if( insertNewOrder()) {
        \App\ModelLikeTable::showUspeh('удачно создали заказ');
    }
    else{
        \App\ModelLikeTable::showNoUspeh("!!!ошибка в добавлении заказа не удалось добавить заказ обратитесь к разаработчику");
    }
}
//проверка на уникальность имени и вывод предупреждения если есть такое имя
if(isset($_POST['testNameUnuque'])){
    if(isset($_POST['nameNewOrder'])){
         $nameOrder = trim( htmlspecialchars($_POST['nameNewOrder']) );

          //если есть заказ с таким именем бросим предупреждение
        if(isExistOrderWithThisName($nameOrder)){
            echo "<script>$('[name=nameOrder]').before('<div class= \'alertDelete backgroundAlertRed \'> есть такое имя заказа рекомендуем изменить на другое </div>');</script>";
        }
        else{
            //если есть предупреждение удалим его
            echo "<script>$('[class~=alertDelete]').remove();</script>";
        }
    }
}
//return orderObject with nameOrderr if it exist, Or return false
function isExistOrderWithThisName( $nameOrderr){
    //найдем заказ с таким именем
    $orderWithThisNameInOrders = \App\Models\Order::isAllowNameOrder($nameOrderr);
    if($orderWithThisNameInOrders){
        return $orderWithThisNameInOrders;
    }else{
        return false;
    }
}
//создание и добавка в базу нового заказа и ответ на сервер
function insertNewOrder(){

    $orNew = new \App\Models\Order();
    $orNew -> isAllowCalculateCost = 0;
    $orNew -> isTrash = 0;

    if (isset($_POST['nameOrder'])) {
        $nameOrder = trim( htmlspecialchars($_POST['nameOrder']) );
        $orNew->name = $nameOrder;
    }
    if (isset($_POST['descriptionOrder'])) {
        $descriptionOrder = trim( htmlspecialchars($_POST['descriptionOrder']));
        $orNew->descriptionOrder = $descriptionOrder;
    }
    if (isset($_POST['idClient'])) {
        $idClient = intval($_POST['idClient']);
        $orNew->idClient = $idClient;
    }
    if (isset($_POST['source'])) {
        $source = intval($_POST['source']);
        $orNew->source = $source;
    }
    if (isset($_POST['orderPrice'])) {
        $orderPrice = htmlspecialchars($_POST['orderPrice']);
        $orNew-> orderPrice = $orderPrice;
    }
    if (isset($_POST['manufacturingPrice'])) {
        $manufacturingPrice = htmlspecialchars($_POST['manufacturingPrice']);
        $orNew-> manufacturingPrice = $manufacturingPrice;
    }
    if (isset($_POST['isCompleted'])) {
        $isCompleted = intval($_POST['isCompleted']);
        $orNew -> isCompleted = $isCompleted;
    }
    if (isset($_POST['isReady'])) {
        $isReady = intval($_POST['isReady']);
        $orNew -> isReady = $isReady;
    }
    if (isset($_POST['isInstall'])) {
        $isInstall = intval($_POST['isInstall']);
        $orNew -> isInstall = $isInstall;
    }

    if(isset($_POST['dateOfOrdering'])){
        $dateOfOrdering = htmlspecialchars($_POST['dateOfOrdering']);
        $orNew -> dateOfOrdering = $dateOfOrdering;
    }
    if(isset($_POST['dateOfComplation'])){
        $dateOfComplation  = htmlspecialchars($_POST['dateOfComplation']);
        $orNew -> dateOfComplation = $dateOfComplation;
    }
    //вставим новый заказ в базу
    $resInsert = $orNew -> insert();
    if($resInsert)
        return true;
    else return false;
}


//поиск клинетов по подобию имени  и выгрузка их в селект выбора клиентов formAddNewOrder.php ['name = idClient']
if(isset($_POST['searchClientLikeName'])){
//    echo "$selectSearchingClientsLikeName";
    if(isset($_POST['likeName'])){
        $likeName = htmlspecialchars($_POST['likeName']);
//        \App\ModelLikeTable::showUspeh("пришел запрос на поиск по имени $likeName");
        $clientsSearcLikeName = \App\Models\Client::searchAllForLikeName($likeName);
        if($clientsSearcLikeName){
            $optionSearchingClients= "<option value=\"0\">выберите клиента</option>";
            foreach ($clientsSearcLikeName as $rowItem){
                $optionSearchingClients .= "<option data-id = '$rowItem->id' value='$rowItem->id'>$rowItem->name</option>";
            }
            echo "$optionSearchingClients";
        }
        else{
            $optionSearchingClients= "<option value=\"0\">такого нет (: </option>";
                    \App\ModelLikeTable::showNoUspeh("не нашли с именем $likeName (:");

            echo "$optionSearchingClients";
        }
    }
}