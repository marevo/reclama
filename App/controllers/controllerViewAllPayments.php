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
if(isset($_POST['searcPaymentshLikeNameClient'])){
    if(isset($_POST['likeValue'])){
       $likeNameClient = htmlspecialchars($_POST['likeValue']);
//        \App\ModelLikeTable::showUspeh("пришел запрос на поиск платежей по подобию имени клиента $likeNameClient");
//    ответ будем направлять в $('#tbViewAllPayments tbody');
        $searchedPaymentsInBase = Payment::getClientsOrdersSumPaymentsCountPaymantsLikeName($likeNameClient);
        $tableTbodeSearchedPayments = createTableTbodyPayments($searchedPaymentsInBase) ;
//        \App\ModelLikeTable::showUspeh($tableTbodeSearchedPayments);
//        die();
        echo "<script>$('#tbViewAllPayments tbody').html(\"$tableTbodeSearchedPayments\");</script>";
    }



}