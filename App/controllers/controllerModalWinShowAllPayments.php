<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 06.09.2017
 * Time: 20:36
 */
require '../../autoload.php';
//запрос из окна просмотра заказа viewOneOrder.php и из окна viewAllPayments.php
if(isset($_POST['loadPaymentForOrder'])){
//    echo "<thead></thead><tbody<tr><td colspan='5'>запрос на получение всех оплат</td></tr></theadtbody>";;
//    die();
    if(isset($_POST['idOrder'])){
        $idOrder = intval($_POST['idOrder']);
        $allPaynentsForThisIdOrder = \App\Models\Payment::getAllPaymentsForOrder($idOrder);
        $tableHeadBody = "<thead><tr><td>idPayment</td><td>idOrder</td><td>idClient</td><td>сумма</td>" .
                                     "<td>date</td>" .
                                      "<td class = 'tdDisplayNone'><span class='glyphicon glyphicon-edit'> удалить</span></td>" .
//                                    "<td class = 'tdDisplayNone'></td>" .
                               "</tr></thead><tbody>";
        //если есть оплаты
        if($allPaynentsForThisIdOrder){
            foreach ($allPaynentsForThisIdOrder as $paymant){
                $tableHeadBody .= "<tr><td>$paymant->id</td><td>$paymant->idOrder</td><td>$paymant->idClient</td><td>$paymant->sumPayment</td><td>$paymant->date</td>"  .
                                      "<td class = 'tdDisplayNone'><button class='btn btn-sm btn-danger btnDeleteThisPayment' data-id ='$paymant->id'><span class = 'glyphicon glyphicon-trash'></span></button></td>" .
                                     "</tr>";
            }
            $tableHeadBody .= "</tbody>";

        }else{
            //нет оплат по данному заказу
            $tableHeadBody .= "<tr><td colspan='5'>пока ничего нет</td></tr></tbody>";
        }
        echo "$tableHeadBody";
    }
}
//запрос из окна просмотра заказа viewOneOrder.php из модального окна на добавление оплаты к заказу
if(isset($_POST['sendPaymentForOrderFromModalWin'])){
    $paymant = new \App\Models\Payment();
    if(isset($_POST['sumPayment']))
        $paymant->sumPayment = htmlspecialchars($_POST['sumPayment']);
    if(isset($_POST['idOrder']))
        $paymant->idOrder = intval($_POST['idOrder']);
    if(isset($_POST['idClient']))
        $paymant->idClient = intval($_POST['idClient']);
    if(isset($_POST['datePayment']))
        $paymant->date = htmlspecialchars($_POST['datePayment']);
    $res = $paymant->insert();
    if($res){
        $payment =  \App\Models\Payment::showSumAllPayments($paymant->idOrder);
        echo "  
            <script>
            ORDER['sumAllPayments'] = $payment;
            allocateOrderField();
            setTimeout(function() {
               $('#modalViewAllPaymentsToThisOrder').modal('hide');
            },1000);
            </script>
        ";
        \App\ModelLikeTable::showUspeh('оплата успешно добавлена');
    }
    else
        \App\ModelLikeTable::showNoUspeh('ошибка, оплата не добавлена');
}

//запрос из окна просмотра заказа viewOneOrder.php запрос из модального окна на удаление оплаты
if(isset($_POST['sendDeletePaymentForOrderFromModalWin'])){
    \App\ModelLikeTable::showUspeh('запрос на удаление');
    if(isset($_POST['idPaymentForDelete'])){
        $idPaymentForDelete = intval($_POST['idPaymentForDelete']);
        $idOrder = \App\Models\Payment::findObjByIdStatic($idPaymentForDelete)->idOrder;

        $res = \App\Models\Payment::deleteObj($idPaymentForDelete);
        if($res){
            $payment =  \App\Models\Payment::showSumAllPayments($idOrder);
            echo "  
            <script>
            ORDER['sumAllPayments'] = $payment;
            allocateOrderField();
            setTimeout(function() {
               $('#modalViewAllPaymentsToThisOrder').modal('hide');
            },1000);
            </script>
        ";
            \App\ModelLikeTable::showUspeh('оплата удалена успешно');
        }else{
            \App\ModelLikeTable::showNoUspeh('ошибка удаления платежа id='+$idPaymentForDelete +'обратисеь к разработчику');
        }
    }
}