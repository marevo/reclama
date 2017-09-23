<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 14.09.2017
 * Time: 14:35
 */
require_once '../../autoload.php';
use App\Models\Order;
use App\Models\Payment;

/* html("<tr><td>2</td><td>фирам Рога и Копыта 2</td><td>23</td><td>вывеска для улицы</td><td data-idOrderForShangedSumAllPayments='23' title='цена заказа 0.00 грн' >8.78</td><td>5</td><td class='text-center'><button class='btn btn-default'  name='btnViewModalAllPaymentThisOrder'  data-payment = '{"idOrder":"23" , "nameOrder":"вывеска для улицы",  "idClient":"2","nameClient":"фирам Рога и Копыта 2",  "sumPayments":"8.78"}'  ><span class="glyphicon glyphicon-eye-open"> оплаты</span></button></td></tr><tr><td>2</td><td>фирам Рога и Копыта 2</td><td>26</td><td>еще один заказ для проверки</td><td data-idOrderForShangedSumAllPayments='26' title='цена заказа 0.00 грн' >1.00</td><td>1</td><td class='text-center'><button class='btn btn-default'  name='btnViewModalAllPaymentThisOrder'  data-payment = '{"idOrder":"26" , "nameOrder":"еще один заказ для проверки",  "idClient":"2","nameClient":"фирам Рога и Копыта 2",  "sumPayments":"1.00"}'  ><span class="glyphicon glyphicon-eye-open"> оплаты</span></button></td></tr><tr><td>2</td><td>фирам Рога и Копыта 2</td><td>2</td><td>название ЛайтБокс для фирмы   Рога и Копыта Чернигов</td><td data-idOrderForShangedSumAllPayments='2' title='цена заказа 120.00 грн' >45.00</td><td>2</td><td class='text-center'><button class='btn btn-default'  name='btnViewModalAllPaymentThisOrder'  data-payment = '{"idOrder":"2" , "nameOrder":"название ЛайтБокс для фирмы   Рога и Копыта Чернигов",  "idClient":"2","nameClient":"фирам Рога и Копыта 2",  "sumPayments":"45.00"}'  ><span class="glyphicon glyphicon-eye-open"> оплаты</span></button></td></tr>");*/
function createTableTbodyPayments($searchedPayments = NULL){
    $tableTbodySearchedPayments="";
    if($searchedPayments){
        foreach ($searchedPayments as $payment){
            $sumDebet = $payment[sumAllPaymentOrder]-$payment[orderPrice];
            $tableTbodySearchedPayments .= "<tr><td class=\"tdDisplayNone\">$payment[idClient]</td><td>$payment[nameClient]</td><td class=\"tdDisplayNone\">$payment[idOrder]</td>" .
                "<td>$payment[nameOrder]</td><td data-idOrder = \"$payment[idOrder]\" title=\"цена заказа $payment[orderPrice] грн\" >$payment[sumAllPaymentOrder]</td>" .
                "<td>$payment[countPayments]</td><td>$sumDebet</td>" .
                "<td class=\"text-center\"><button class=\"btn btn-default\" name=\"btnViewModalAllPaymentThisOrder\" " .
                " data-idOrder = $payment[idOrder] " .
                " ><span class=\"glyphicon glyphicon-eye-open\"> оплаты</span></button></td></tr>";
        }
    }
    else{
        $tableTbodySearchedPayments .= "<tr><td colspan=\"7\">по таким данным  ничего нет (</td> </tr>";
    }
    return $tableTbodySearchedPayments;
}

//поиск только по имени
if(isset($_POST['searchPaymentsLikeName'])){
    //    'likeNameClient'
    //1 - getPaymentsClientsOrdersSumPaymentsCountPaymantsLikeName($likeName)
    if(isset($_POST['likeNameClient'])){
        $likeNameClient = htmlspecialchars($_POST['likeNameClient']);
        $searchedPaymentsInBase = Payment::getPaymentsClientsOrdersSumPaymentsCountPaymantsLikeName($likeNameClient);
        $tableTbodeSearchedPayments = createTableTbodyPayments($searchedPaymentsInBase) ;
//        \App\ModelLikeTable::showUspeh($tableTbodeSearchedPayments);
//        die();
        echo "<script>$('#tbViewAllPayments tbody').html('".$tableTbodeSearchedPayments."');</script>";
    }
}

//поиск по имени и 2 датам
if(isset($_POST['searchPaymentsLikeNameClientDateFromDateTo'])){
//    'likeNameClient', 'dateFrom', 'dateTo'
    //2 - getPaymentsClientsOrdersSumPaymentsCountPaymantsLikeNameDateFromDateTo($likeNameClient, $dateFrom, $dateTo);
    if(isset($_POST['likeNameClient']) && isset($_POST['dateFrom']) && isset($_POST['dateTo'])){
        $likeNameClient = htmlspecialchars($_POST['likeNameClient']);
        $dateFrom = htmlspecialchars($_POST['dateFrom']);
        $dateTo = htmlspecialchars($_POST['dateTo']);
        $searchedPaymentsInBase = Payment::getPaymentsClientsOrdersSumPaymentsCountPaymantsLikeNameDateFromDateTo($likeNameClient, $dateFrom, $dateTo);
        $tableTbodeSearchedPayments = createTableTbodyPayments($searchedPaymentsInBase) ;
//        \App\ModelLikeTable::showUspeh($tableTbodeSearchedPayments);
//        die();
        echo "<script>$('#tbViewAllPayments tbody').html('".$tableTbodeSearchedPayments."');</script>";

    }
}



//поиск только по датам 'searchPaymentsDateFromDateTo', 'dateFrom', 'dateTo'
if(isset($_POST['searchPaymentsDateFromDateTo'])){
    //3 - getPaymentsClientsOrdersSumPaymentsCountPaymantsDateFromDateTo($dateFrom, $dateTo);
    if(isset($_POST['dateFrom']) && isset($_POST['dateTo'])){
        $dateFrom = htmlspecialchars($_POST['dateFrom']);
        $dateTo = htmlspecialchars($_POST['dateTo']);
        $searchedPaymentsInBase = Payment::getPaymentsClientsOrdersSumPaymentsCountPaymantsDateFromDateTo($dateFrom, $dateTo);
        $tableTbodeSearchedPayments = createTableTbodyPayments($searchedPaymentsInBase) ;
//        \App\ModelLikeTable::showUspeh($tableTbodeSearchedPayments);
//        die();
        echo "<script>$('#tbViewAllPayments tbody').html('$tableTbodeSearchedPayments');</script>";

    }
}

//поиск по дате "от" и платежей по имени
if(isset($_POST['searchPaymentsDateMoreDateFromAndLikeName'])){
    //4 - getPaymentsClientsOrdersSumPaymentsCountPaymantsDateMoreDateFromAndLikeName($dateFrom, $likeName)
    if(isset($_POST['dateFrom']) && isset($_POST['likeNameClient'])){
        $dateFrom = htmlspecialchars($_POST['dateFrom']);
        $likeNameClient = htmlspecialchars($_POST['likeNameClient']);
        $searchedPaymentsInBase = Payment::getPaymentsClientsOrdersSumPaymentsCountPaymantsDateMoreDateFromAndLikeName($dateFrom, $likeName);
        $tableTbodeSearchedPayments = createTableTbodyPayments($searchedPaymentsInBase) ;
//        \App\ModelLikeTable::showUspeh($tableTbodeSearchedPayments);
//        die();
        echo "<script>$('#tbViewAllPayments tbody').html('$tableTbodeSearchedPayments');</script>";
    }
}


//поиск по дате "от" всех платежей всех клиентов
if(isset($_POST['searchPaymentsDateMoreDateFrom'])) {
    //5 - getPaymentsClientsOrdersSumPaymentsCountPaymantsDateMoreDateFrom($dateFrom);
    if(isset($_POST['dateFrom'])){
        $dateFrom = htmlspecialchars($_POST['dateFrom']);
        $searchedPaymentsInBase = Payment::getPaymentsClientsOrdersSumPaymentsCountPaymantsDateMoreDateFrom($dateFrom);
        $tableTbodeSearchedPayments = createTableTbodyPayments($searchedPaymentsInBase) ;
//        \App\ModelLikeTable::showUspeh($tableTbodeSearchedPayments);
//        die();
        echo "<script>$('#tbViewAllPayments tbody').html('$tableTbodeSearchedPayments');</script>";
    }
}

//поиск платежей по клиенту до даты "до"
if(isset($_POST['searchPaymentsDateLessDateToAndLikeName'])) {
    //6 - getPaymentsClientsOrdersSumPaymentsCountPaymantsDateLessDateToAndLikeName($dateTo, $likeName);
    if(isset($_POST['dateTo']) && $_POST['likeNameClient']){
        $dateTo = htmlspecialchars($_POST['dateTo']);
        $likeNameClient = htmlspecialchars($_POST['likeNameClient']);
        $searchedPaymentsInBase = Payment::getPaymentsClientsOrdersSumPaymentsCountPaymantsDateLessDateToAndLikeName($dateTo, $likeName);
        $tableTbodeSearchedPayments = createTableTbodyPayments($searchedPaymentsInBase) ;
//        \App\ModelLikeTable::showUspeh($tableTbodeSearchedPayments);
//        die();
        echo "<script>$('#tbViewAllPayments tbody').html('$tableTbodeSearchedPayments');</script>";
    }
}

//поиск всех платежей по всем клиентам до даты "до"
if(isset($_POST['searchPaymentsDateLessDateTo'])) {
    //7 - getPaymentsClientsOrdersSumPaymentsCountPaymantsDateLessDateTo($dateTo);
    if(isset($_POST['dateTo']) ){
        $dateTo = htmlspecialchars($_POST['dateTo']);
        $searchedPaymentsInBase = Payment::getPaymentsClientsOrdersSumPaymentsCountPaymantsDateLessDateTo($dateTo);
        $tableTbodeSearchedPayments = createTableTbodyPayments($searchedPaymentsInBase) ;
//        \App\ModelLikeTable::showUspeh($tableTbodeSearchedPayments);
//        die();
        echo "<script>$('#tbViewAllPayments tbody').html('$tableTbodeSearchedPayments');</script>";
    }
}

//поиска заказов для модального окна в modalFormAddNewPaymentToBase загружать в таблицу
if(isset($_POST['searchOrderFromModalFormAddNewPaymentToBase'])){
    if( isset($_POST['likeNameClientLikeNameOrder']) ) {
        $likeNameClientOrLikeNameOrder = htmlspecialchars($_POST['likeNameClientLikeNameOrder']);
        $ArrNameClientNameOrder = explode(" ", $likeNameClientOrLikeNameOrder );
        if($ArrNameClientNameOrder[0])
           $likeNameClient = $ArrNameClientNameOrder[0];
        else $likeNameClient = "" ;
        if($ArrNameClientNameOrder[1])
            $likeNameOrder = $ArrNameClientNameOrder[1];
        else $likeNameOrder = "" ;
//        $likeNameClient  = "пуп";
//        $likeNameOrder ="вывеска";
//        \App\ModelLikeTable::showUspeh("пришел запрос на поиск заказов по подобию в клиенте \"$likeNameClient\" и подобию в заказе \"$likeNameOrder\" за дату ".htmlspecialchars($datePayment)."");

        $arrayObjOrders = Order::getOrdersLikeNameOrderOrLikeNameClient($likeNameClient,$likeNameOrder);
//        var_dump($arrayObjOrders);
//        die();
        if($arrayObjOrders){
            //$optionsFinding = "<option value='0'>выберите из списка</option>";
            $findingOrders ="";
            foreach ($arrayObjOrders as $order){
              //  $optionsFinding .= "<option value = '$order[idOrder]'>заказ $order[nameOrder] клиент $order[idClent] $order[nameClient] $order[orderIsReady]</option>";
                $findingOrders  .= "<tr><td>$order[idClient]</td><td>$order[nameClient]</td><td>$order[idOrder]</td><td>$order[nameOrder]</td>" .
                    "<td>$order[orderIsReady]</td><td><button class='btn btn-sm btn-success ' data-idOrder='$order[idOrder]'>выбрать</button></td></tr>";
            }
        }
        else{
            //$optionsFinding = "<option value='0'>ничего нет</option>";
            $findingOrders="<tr><td colspan='5'>ничего нет (</td></tr>";
        }
        
        echo "<script>
$('#tbFindingOrderByLikeNameClientLikeNameOrderModalFormAddNewPaymentToBase tbody').html(\"$findingOrders\");
</script>";

        
    }
}

//добавка оплаты из модального окна modalFormAddPaymentToBase.html
//'addPaymentToBaseModalFormAddPaymentToBase','idOrder','sumPayment','datePayment'
if(isset($_POST['addPaymentToBaseModalFormAddPaymentToBase'])){
    if(isset($_POST['idOrder']) && isset($_POST['sumPayment']) && isset($_POST['datePayment'])){
        $idOrder = intval($_POST['idOrder']);
        $sumPayment = htmlspecialchars($_POST['sumPayment']);
        $datePayment = htmlspecialchars($_POST['datePayment']);
        \App\ModelLikeTable::showUspeh("пришел запрос на добавку оплаты к заказу idOrder=$idOrder sumPayment=$sumPayment datePayment=$datePayment ");
        $obOrderForAddPayment = Order::findObjByIdStatic($idOrder);
        $idClient = $obOrderForAddPayment->idClient;
        $paymentForAddToBase = new Payment();
        $paymentForAddToBase->date = $datePayment;
        $paymentForAddToBase->sumPayment = $sumPayment;
        $paymentForAddToBase->idOrder = $idOrder;
        $paymentForAddToBase->idClient = $idClient;
        $res = $paymentForAddToBase->insert();
        if($res){
            \App\ModelLikeTable::showUspeh("успешно добавили оплату idOrder=$idOrder idClient=$idClient sumPayment=$sumPayment datePayment=$datePayment ");
        }
        else{
            \App\ModelLikeTable::showNoUspeh("не удалось добавить оплату idOrder=$idOrder idClient=$idClient sumPayment=$sumPayment datePayment=$datePayment ");
        }
    }
}