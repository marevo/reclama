<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 06.09.2017
 * Time: 20:36
 */
require '../../autoload.php';
//запрос из окна просмотра заказа viewOneOrder.php
if(isset($_POST['loadPaymentForOrder'])){
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

//запрос из окна просмотра заказа viewAllPayments.php по конкретному заказу
if(isset($_POST['loadPaymentForOrderFromViewAllPayments'])){
    if(isset($_POST['idOrder'])){
        $idOrder = intval($_POST['idOrder']);
        $allPaynentsForThisIdOrder = \App\Models\Payment::getAllPaymentsForOrder($idOrder);
        $tableHeadBody = "<thead><tr><td>idPayment</td><td>idOrder</td><td>idClient</td><td>сумма</td>" .
            "<td>date</td>" .
            "<td class = 'tdDisplayNone'><span class='glyphicon glyphicon-edit'> удалить</span></td>" .
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
        echo "<script>$('#tableAllPaymentsForThisOrder').html(\"$tableHeadBody\");</script>";

        //    получим сумму всех оплат по этому заказу и  количество  всех оплат для этого заказа
        $sumAllPaymentsForThisOrder = \App\Models\Payment::getSumAllPaymentsForOrder($idOrder);
        $countPaymentsForThisOrder =\App\Models\Payment::getCountPaymentsForOrder($idOrder);
        //передадим на клиент в модальное и главное(viewAllPayments.php) окна сумму всех оплат по этому заказу
        echo "<script>$('#modalViewAllPaymentsToThisOrder').find('[data-sumpayments]').text('$sumAllPaymentsForThisOrder');</script>";
        //передадим в таблицу всех оплат по всем заказам новую сумму всех оплат по заказу $idOrder
        echo "<script>$('#tbViewAllPayments').find('[data-idOrderForShangedSumAllPayments=$idOrder]').text('$sumAllPaymentsForThisOrder');</script>";

        //передадим на клиент в главное окно (viewAllPayments.php) количиство проплат по данному заказу
        echo "<script>$('#tbViewAllPayments').find('[data-idOrderForShangedSumAllPayments=$idOrder]').next().text('$countPaymentsForThisOrder');</script>";
    }
}


//запрос из окна просмотра заказа viewOneOrder.php из модального окна на добавление оплаты к заказу в viewOrder.php
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
        $payment =  \App\Models\Payment::getSumAllPaymentsForOrder($paymant->idOrder);
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

//запрос из окна просмотра модального окна всех платежей по заказу, которое всплывает в viewAllPayments.php на добавление оплаты к заказу
if(isset($_POST['sendPaymentForOrderFromModalWinFromViewAllPayments'])){
    $paymant = new \App\Models\Payment();
    if(isset($_POST['sumPayment']))
        $paymant->sumPayment = htmlspecialchars($_POST['sumPayment']);
//    else
//        \App\ModelLikeTable::showNoUspeh('не пришло значение суммы оплаты');

    if(isset($_POST['idOrder']))
        $paymant->idOrder = intval($_POST['idOrder']);
//    else
//        \App\ModelLikeTable::showNoUspeh('не пришло значение idOrder');

    if(isset($_POST['idClient']))
        $paymant->idClient = intval($_POST['idClient']);
//    else
//        \App\ModelLikeTable::showNoUspeh('не пришло значение idClient');

    if(isset($_POST['datePayment']))
        $paymant->date = htmlspecialchars($_POST['datePayment']);
//    else
//        \App\ModelLikeTable::showNoUspeh('не пришло значение datePayment');


    //надо проверить заказ закрыт ? (успешно или не успешно неважно isReady = 1, isReady = 2 ) 
//    если заказ не закрыт - то можем добавить оплату isReady != 1, isReady != 2
    $thisOrder = \App\Models\Order::findObjByIdStatic($paymant->idOrder);
    if($thisOrder->isReady == 1 || $thisOrder->isReady == 2){
//        случай когда заказ закрыт
        \App\ModelLikeTable::showNoUspeh('заказ закрыт !,поэтому оплата не добавлена');
    }else{
        \App\ModelLikeTable::showUspeh("можно добавлять оплату sumPayment = $paymant->sumPayment грн, idOrder= $paymant->idOrder  idClient= $paymant->idClient  datePayment= $paymant->date");
        $res = $paymant->insert();
        if($res){
            //запросим заново сумму всех платежей
            $payment =  \App\Models\Payment::getSumAllPaymentsForOrder($paymant->idOrder);
            //запросим заново количество оплат по заказу
            $countPayments = \App\Models\Payment::getCountPaymentsForOrder($thisOrder->id);
            echo "  
            <script>
                      $('#modalViewAllPaymentsToThisOrder').modal('hide');
                      setTimeout(function() {
                           $('#modalViewAllPaymentsToThisOrder').modal('show');
                      },1000);
           </script>
            ";
            \App\ModelLikeTable::showUspeh('оплата успешно добавлена');
        }
        else
            \App\ModelLikeTable::showNoUspeh('ошибка, оплата не добавлена');

    }
}


//запрос из окна просмотра заказа viewOneOrder.php запрос из модального окна на удаление оплаты
if(isset($_POST['sendDeletePaymentForOrderFromModalWin'])){
    \App\ModelLikeTable::showUspeh('запрос на удаление');
    if(isset($_POST['idPaymentForDelete'])){
        $idPaymentForDelete = intval($_POST['idPaymentForDelete']);
        $idOrder = \App\Models\Payment::findObjByIdStatic($idPaymentForDelete)->idOrder;

        $res = \App\Models\Payment::deleteObj($idPaymentForDelete);
        if($res){
            $payment =  \App\Models\Payment::getSumAllPaymentsForOrder($idOrder);
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

//запрос из окна просмотра всех платежей viewAllPayments.php запрос из модального окна на удаление оплаты
//маркер запроса sendDeletePaymentForOrderFromModalWinFromViewAllPayments
if(isset($_POST['sendDeletePaymentForOrderFromModalWinFromViewAllPayments'])){
    \App\ModelLikeTable::showUspeh('запрос на удаление платежа с id='.$_POST['idPaymentForDelete']);
    if(isset($_POST['idPaymentForDelete'])){
        $idPaymentForDelete = intval($_POST['idPaymentForDelete']);
        //найдем idOrder id заказа который у которого будем удалять платеж
        $idOrder = \App\Models\Payment::findObjByIdStatic($idPaymentForDelete)->idOrder;
        $res = \App\Models\Payment::deleteObj($idPaymentForDelete);
       /* if($res){
            //если удаление успешно, то нужно удалить строку из таблицы всех платежей в модальном окне
            // и передать новое значение сумм всех оплат по этому заказу
            $payment =  \App\Models\Payment::getSumAllPaymentsForOrder($idOrder);
            //новое количество оплат              $countPayments =
            $countPayments = \App\Models\Payment::getCountPaymentsForOrder($idOrder);
            \App\ModelLikeTable::showUspeh("количество платежей после удаления стало $countPayments");
            //удалим строку с удаленным платежем из таблицы в модальном окне
            echo "<script>$('button[data-id=$idOrder]').parent().parent().remove();</script>";
//            передадим в модальное окно новую сумму всех оплат по этому заказу
            echo "<script>$('#modalViewAllPaymentsToThisOrder').find('[data-sumpayments]').text('$payment');</script>";
             //передадим в таблицу всех оплат по всем заказам новую сумму всех оплат по заказу $idOrder
            echo "<script>$('#tbViewAllPayments').find('[data-idOrderForShangedSumAllPayments=$idOrder]').text('$payment');</script>";
            //передадим новое количество оплат по заказу
            echo"<script>$('#tbViewAllPayments').find('[data-idOrderForShangedSumAllPayments=$idOrder]').next().text('$countPayments');</script>";
             
            \App\ModelLikeTable::showUspeh('оплата удалена успешно');
        }else{
            \App\ModelLikeTable::showNoUspeh('ошибка удаления платежа id='+$idPaymentForDelete +'обратисеь к разработчику');
        }
       */

        if($res){
            //если удаление успешно, то нужно удалить строку из таблицы всех платежей в модальном окне
            // и передать новое значение сумм всех оплат по этому заказу
            //так как мы при показе модального окна производим новый запрос именно по этому же заказу который отслеживаем по нажатии кнопки просмотр,
            // то для упрощения скроем и покажем модальное окно через функцию js modalAllPaymentsForThisOrder_HIDE_SHOW()
//            echo "<script>$('#modalViewAllPaymentsToThisOrder').modal('hide');</script>";
              echo "<script>modalAllPaymentsForThisOrder_HIDE_SHOW();</script>";
//
//            echo "<script> setTimeout(function(){$('#modalViewAllPaymentsToThisOrder').modal('show')},1000);</script>";
//            echo "<script> $('#modalViewAllPaymentsToThisOrder').modal('show');</script>";

        }else{
            \App\ModelLikeTable::showNoUspeh('ошибка удаления платежа id='+$idPaymentForDelete +'обратисеь к разработчику');
        }
    }
}
