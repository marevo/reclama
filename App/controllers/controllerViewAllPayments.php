<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 14.09.2017
 * Time: 14:35
 */
require '../../autoload.php';
use App\Models\Payment;

function createTableTbodyPayments($searchedPayments = NULL){
    $tableTbodySearchedPayments="";
    if($searchedPayments){
        foreach ($searchedPayments as $payment){
            $tableTbodySearchedPayments .= "<tr><td>$payment[idClient]</td><td>$payment[nameClient]</td><td>$payment[idOrder]</td>" .
                "<td>$payment[nameOrder]</td><td data-idOrderForShangedSumAllPayments='$payment[idOrder]' title='цена заказа $payment[orderPrice] грн' >$payment[sumAllPaymentOrder]</td>" .
                "<td>$payment[countPayments]</td>" .
                "<td class='text-center'><button class='btn btn-default' " .
                " name='btnViewModalAllPaymentThisOrder' " .
                " data-payment= { 'idOrder ' : '$payment[idOrder]' , 'nameOrder' : '$payment[nameOrder]' , " .
                " 'idClient' : '$payment[idClient]' , 'nameClient' : '$payment[nameClient]' , " .
                " 'sumPayments' : '$payment[sumAllPaymentOrder]'} " .
                " ><span class='glyphicon glyphicon-eye-open'> оплаты</span></button></td></tr>";
        }
    }
    else{
        $tableTbodySearchedPayments .= "<tr>по таким данным  ничего нет ( </tr>";
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
        echo "<script>$('#tbViewAllPayments tbody').html(\"$tableTbodeSearchedPayments\");</script>";
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
        echo "<script>$('#tbViewAllPayments tbody').html(\"$tableTbodeSearchedPayments\");</script>";
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

