<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 08.09.2017
 * Time: 22:02
 */
require '../../autoload.php';
function insertNewOrder()
{
    if (isset($_POST['submitFromFormOneOrder'])) {
        echo 'пришел запрос на добавление заказа <br/>';
        $orNew = new \App\Models\Order();
        $orNew -> isAllowCalculateCost = 0;
        $orNew -> isTrash = 0;
        if (isset($_POST['nameOrder'])) {
            $nameOrder = trim( htmlspecialchars($_POST['nameOrder']) );
            if (\App\Models\Order::isAllowNameOrder($nameOrder) == false) {
                $orNew->name = $nameOrder;
                echo 'заказа с именем '.$orNew->name.' нет, а значит  сможем добавить заказ </br>';
            }
            else echo "<script>$('#rezShow').text('есть такое имя заказа поменяйте на другое иначе вы не сможете создать заказ</br> нельзя создавать заказы с одинаковыми названиями');</script>";
        }
        if (isset($_POST['descriptionOrder'])) {
            $descriptionOrder =trim( htmlspecialchars($_POST['descriptionOrder']));
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
        if($resInsert != false){
            echo "<script>
                 $('.divForAnswerServer').html('удчно создали заказ');
</script>";
            $paymentFirst = new \App\Models\Payment();
            $paymentFirst -> date = $dateOfOrdering;
            $paymentFirst -> idClient = $orNew -> idClient;
            //получим объект класса Order
            $myOrder = \App\Models\Order::isAllowNameOrder($nameOrder);
            if($myOrder != false){
                //удалось создать заказ и вставить его в базу
                $myOrderId = $myOrder->id;
                $paymentFirst -> idOrder = $myOrderId;
                $resInsertPay = $paymentFirst ->insert();
                if($resInsertPay != false ){
                    $allPayments = \App\Models\Payment::showSumAllPayments($myOrderId);
                    echo "<br/>добавили оплату по заказу $nameOrder сумма всех оплат = $allPayments";
                }
                else echo "обратитесь к разработчику!!! ошибка добавления проверочной суммы при создании заказа( вы не сможете найти его в заказах)";
            }
            else echo "!!! ошибка. обратитесь к разработчику!!! заказ был добавлен успешно, но не нашли такого заказа";
        }
        else{
            echo"!!!ошибка в добавлении заказа не удалось добавить заказ обратитесь к разаработчику";
        }


//        if (isset($_POST['submitFromFormOneOrder']))
//            foreach ($orNew as $k => $value) {
//                echo "<br/>$k--- $value";
//            }
    }
}
\App\ModelLikeTable::showUspeh('пришел запрос на добвку в базу новго заказа');

//if(isset($_POST['controlka'])){
//    \App\ModelLikeTable::showUspeh('пришел запрос на добвку в базу новго заказа');
//}