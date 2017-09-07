<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 06.09.2017
 * Time: 20:36
 */
require '../../autoload.php';

if(isset($_POST['loadPaymentForOrder'])){
//    echo "<thead></thead><tbody<tr><td colspan='5'>запрос на получение всех оплат</td></tr></theadtbody>";;
//    die();
    if(isset($_POST['idOrder'])){
        $idOrder = intval($_POST['idOrder']);
        $allPaynentsForThisIdOrder = \App\Models\Payment::getAllPaymentsForOrder($idOrder);
        $tableHeadBody = "<thead><tr><td>idPayment</td><td>idOrder</td><td>idClient</td><td>sumPayment</td><td class='tdDisplayNone'>новая сумма</td><td>внести</td><td>date</td><td><span class='glyphicon glyphicon-edit'></span></td><td><span class='glyphicon glyphicon-trash'></span></td></span></td></tr></thead><tbody>";
        //если есть оплаты
        if($allPaynentsForThisIdOrder){
            foreach ($allPaynentsForThisIdOrder as $paymant){
                $tableHeadBody .= "<tr><td>$paymant->id</td><td>$paymant->idOrder</td><td>$paymant->idClient</td><td>$paymant->sumPayment</td><td><input id='idNewSumPay'/></td><td>$paymant->date</td></tr>";
            }
            $tableHeadBody .= "</tbody>";

        }else{
            //нет оплат по данному заказу
            $tableHeadBody .= "<tr><td colspan='5'>пока ничего нет</td></tr></tbody>";
        }
        echo "$tableHeadBody";
    }
}