<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 14.09.2017
 * Time: 14:35
 */
require '../../autoload.php';
use App\Models\Payment;
/* html("<tr><td>2</td><td>фирам Рога и Копыта 2</td><td>23</td><td>вывеска для улицы</td><td data-idOrderForShangedSumAllPayments='23' title='цена заказа 0.00 грн' >8.78</td><td>5</td><td class='text-center'><button class='btn btn-default'  name='btnViewModalAllPaymentThisOrder'  data-payment = '{"idOrder":"23" , "nameOrder":"вывеска для улицы",  "idClient":"2","nameClient":"фирам Рога и Копыта 2",  "sumPayments":"8.78"}'  ><span class="glyphicon glyphicon-eye-open"> оплаты</span></button></td></tr><tr><td>2</td><td>фирам Рога и Копыта 2</td><td>26</td><td>еще один заказ для проверки</td><td data-idOrderForShangedSumAllPayments='26' title='цена заказа 0.00 грн' >1.00</td><td>1</td><td class='text-center'><button class='btn btn-default'  name='btnViewModalAllPaymentThisOrder'  data-payment = '{"idOrder":"26" , "nameOrder":"еще один заказ для проверки",  "idClient":"2","nameClient":"фирам Рога и Копыта 2",  "sumPayments":"1.00"}'  ><span class="glyphicon glyphicon-eye-open"> оплаты</span></button></td></tr><tr><td>2</td><td>фирам Рога и Копыта 2</td><td>2</td><td>название ЛайтБокс для фирмы   Рога и Копыта Чернигов</td><td data-idOrderForShangedSumAllPayments='2' title='цена заказа 120.00 грн' >45.00</td><td>2</td><td class='text-center'><button class='btn btn-default'  name='btnViewModalAllPaymentThisOrder'  data-payment = '{"idOrder":"2" , "nameOrder":"название ЛайтБокс для фирмы   Рога и Копыта Чернигов",  "idClient":"2","nameClient":"фирам Рога и Копыта 2",  "sumPayments":"45.00"}'  ><span class="glyphicon glyphicon-eye-open"> оплаты</span></button></td></tr>");*/
function createTableTbodyPayments($searchedPayments = NULL){
    $tableTbodySearchedPayments="";
    if($searchedPayments){
        foreach ($searchedPayments as $payment){
            $tableTbodySearchedPayments .= "<tr><td>$payment[idClient]</td><td>$payment[nameClient]</td><td>$payment[idOrder]</td>" .
                "<td>$payment[nameOrder]</td><td data-idOrder = \"$payment[idOrder]\" title=\"цена заказа $payment[orderPrice] грн\" >$payment[sumAllPaymentOrder]</td>" .
                "<td>$payment[countPayments]</td>" .
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
// здесь нет используется только для образца для функция с подобным назаванием для проверки вывода - так идет сборка таблицы в viewAllPayments.php
function createTableTbodyPayments_2($searchedPayments = NULL){
    $tableTbodySearchedPayments="";
    if($searchedPayments){
        foreach ($searchedPayments as $payment){
//                                 $paymentItem = new Payment($payment->id,$payment->idOrder,$payment->idClient,$payment->sumPayment,$payment->date);
            $tableTbodySearchedPayments .= "<tr><td>$payment[idClient]</td><td>$payment[nameClient]</td><td>$payment[idOrder]</td>" .
                "<td>$payment[nameOrder]</td><td data-idOrder ='$payment[idOrder]' title='цена заказа $payment[orderPrice] грн' >$payment[sumAllPaymentOrder]</td>" .
                "<td>$payment[countPayments]</td>" .
                "<td class='text-center'><button class='btn btn-default'" .
                " data-idOrder='$payment[idOrder]' name='btnViewModalAllPaymentThisOrder'" .
                " ><span class='glyphicon glyphicon-eye-open'> оплаты</span></button></td></tr>";
        }

    }
    else{
        $tableTbodySearchedPayments .= "<tr>по таким данным  ничего нет ( </tr>";
    }
    return $tableTbodySearchedPayments;
}
//*/здесь нет используется только для образца для функция с подобным назаванием для проверки вывода - так идет сборка таблицы в viewAllPayments.php
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
        echo "<script>$('#tbViewAllPayments tbody').html('$tableTbodeSearchedPayments');</script>";
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
        echo "<script>$('#tbViewAllPayments tbody').html(\"$tableTbodeSearchedPayments\");</script>";
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
        echo "<script>$('#tbViewAllPayments tbody').html(\"$tableTbodeSearchedPayments\");</script>";
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
        echo "<script>$('#tbViewAllPayments tbody').html(\"$tableTbodeSearchedPayments\");</script>";
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
        echo "<script>$('#tbViewAllPayments tbody').html(\"$tableTbodeSearchedPayments\");</script>";
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
        echo "<script>$('#tbViewAllPayments tbody').html(\"$tableTbodeSearchedPayments\");</script>";
    }
}

