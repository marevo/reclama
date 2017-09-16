<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 14.09.2017
 * Time: 13:40
 */
require '../autoload.php';
use App\Models\Payment;
use App\Models\Client;
use App\Models\Order;
?>
<!DOCTYPE HTML>
<html lang="ru-RU">
<?php
include('../head.html');
?>
<body>
<div class="container">
    <div class="row">
        <?php require_once('header.html'); ?>
    </div>
    <!--        добавление панели навигации-->
    <div class="row"><!-- навигация -->
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?php
            require_once('../navigation.html');
            ?>
            <script>
                showLi('платежи');
            </script>
        </div>
        <!-- конец навигации -->
    </div>
    <!--строка показа времени и показа результата добавки материала в базу  -->
    <?php  include_once '../App/html/forDisplayTimeShowAnswerServer.html'?>
    <div class="row"><!-- основной блок контета состоит из 2 колонок слева и 10 колонок справа -->
        <div class="col-lg-2 backForDiv"> <!-- начало доп блока слева-->
            этот див слева от таблицы в нем можно расположить дополнительные кнопки добавить редактировать удалить
        </div><!-- конец доп блока слева-->
        <div class="col-lg-10 backForDiv">
            <?php
            //                получим из таблицы всех платежей и покажем из через вызов быстрого показа в трэйте FastViewTable.php
//            echo \App\Models\Payment::showAllFromTable('tablePayments',\App\Models\Payment::findAll());
            ?>
            <div class="row headingContent"><!--строка для отображения названия страницы где находится пользователь -->
                <div class="col-lg-10   col-md-10 col-sm-10 col-xs-10   text-center ">платежи</div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center"></div>
            </div>
            <div class="row rowSearch" ><!-- строка поиска-->
                <!--  сторка для поиска платежей по name клиента или по дате  -->
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"><label for="inputFindPaymentForNameClient"> по клиенту </label></div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"><label for="inputFindPaymentForNameClient"> </label></div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"><label for="inputFindPaymentForDatePaymentFromDate"> за период </label></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"><input type="text" id="inputFindPaymentForNameClient" placeholder="по клиенту"/></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"><button id="btnSearchPaymentForClient" class="btn-primary">искать </button></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"><input type="date" id="inputFindPaymentForDatePaymentFromDate" placeholder="начало"/></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"><input type="date" id="inputFindPaymentForDatePaymentToDate" placeholder="конец"/></div>
<!--                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"><button id="btnSearchPaymentForDate" class="btn-primary">искать </button></div>-->
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                    <label for="makeNewClient"  class="text-center">новый платеж</label>
                    <div title="создать новый платеж" id="makeNewPayment"></div>
                    <a href='formAddNewPaymentToBase.html'> <div class="text-center"> <span class='glyphicon glyphicon-plus'></span></div></a>
                </div>
            </div><!-- конец блока строки поиска  -->

            <div class="row backForDiv divForTable">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?php
                    //Найти клиента, заказ, сумму оплат по заказу, количество оплат по заказу выводом по алфавиту в названии (имени) клиента
                    //idClient , nameClient , idOrder , nameOrder , SUM(sumPayment) , COUNT(sumPayment)
                    $allPaymentsInBase = Payment::getClientsOrdersSumPaymentsCountPaymants();
                    if($allPaymentsInBase){
                        $tableAllPayments = "<table id='tbViewAllPayments'><thead><tr><td>idClient</td><td>nameClient</td>" .
                            "<td>idOrder</td><td>nameOrder</td><td>сумма</td><td>ко-во оплат</td>" .
                            "<td class='text-center'><span class='glyphicon glyphicon-eye-open'> оплаты</span></td></tr></thead><tbody>";
                        foreach ($allPaymentsInBase as $payment){
//                                 $paymentItem = new Payment($payment->id,$payment->idOrder,$payment->idClient,$payment->sumPayment,$payment->date);
                                $tableAllPayments .= "<tr><td>$payment[idClient]</td><td>$payment[nameClient]</td><td>$payment[idOrder]</td>" .
                                    "<td>$payment[nameOrder]</td><td data-idOrderForShangedSumAllPayments='$payment[idOrder]' title='цена заказа $payment[orderPrice] грн' >$payment[sumAllPaymentOrder]</td>" .
                                    "<td>$payment[countPayments]</td>" .
                                    "<td class='text-center'><button class='btn btn-default'" .
                                    " name='btnViewModalAllPaymentThisOrder'" .
                                    " data-payment='{\"idOrder\":\"$payment[idOrder]\",\"nameOrder\":\"$payment[nameOrder]\", " .
                                    " \"idClient\":\"$payment[idClient]\",\"nameClient\":\"$payment[nameClient]\", " .
                                    " \"sumPayments\":\"$payment[sumAllPaymentOrder]\" }' " .
                                    " ><span class='glyphicon glyphicon-eye-open'> оплаты</span></button></td></tr>";
                        }
                        $tableAllPayments .= "</tbody></table>";
                    }
                    else{
                        $tableAllPayments = "пока ничего нет (";
                    }
                    echo $tableAllPayments;
                    ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- модальное окно для удаления   -->
    <div id="modalWinForDeletePayment" class="modal fade" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-center">удалить оплату навсегда!
                    <button class="close" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row" style="background-color: #c0c7d2;">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="col-lg-12 text-center">хотите удалить эту оплату навсегда ?</div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 text-center " id="modalNameClient"> название клиента</div>
                                    <div style="display: block;" class="col-lg-12 text-center " id="modalIdPayment"> id платежа</div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-center"><button name="btnDeletePayment"
                                                                                                         class="btn btn-danger">да</button></div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-center"><button class="btn btn-default" data-dismiss="modal">нет</button></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!--modal-body-->
            </div><!--modal content-->
        </div><!--modal-dialog-->
    </div><!--id="modalWinForDeleteMat" modal-fade -->

    <!-- модальное окно для показа всех оплат по клику кнопки -->
    <div id="modalViewAllPaymentsToThisOrder" class="modal fade" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-center" >показываем все оплаты к заказу
                    <button class="close" data-dismiss="modal">x</button>
                    <div>
                        <div class=" uspeh text-center "><span class="glyphicon glyphicon-import "> успешно</span></div>
                        <div class=" noUspeh text-center "><span class="glyphicon glyphicon-alert "> ошибка обратитесь к разработчику</span></div>
                        <!-- в поле с классом divForAnswerServer будем получать ответы сервера (script ) -->
                        <div class="divShowAnswerServer">ответ сервера</div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <!--<header>-->
                        <div class="row">
                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1" data-idOrder="idOrder">idOrder</div>
                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1" >заказ</div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 "  data-nameOrder="nameOrder">nameOrder</div>
                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1" >клиент</div>
                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1" data-idClient="idClient">idClient</div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 "  data-nameClient="nameClient">nameClient</div>

                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                                <button  class="btn btn-sm btn-primary btn-sm" id="btnModalShowButtonsTrashInTable" title="разрешить удаление оплат">
                                    <span class="glyphicon glyphicon-edit"> править</span> </button>
                            </div>
                        </div>
                        <!--</header>-->
                        <div class="row">
                            <div class="col-lg-12" >
                                <table id="tableAllPaymentsForThisOrder"><!--таблица для показа всех оплат к этому заказу --></table>
                            </div>
                        </div>
                        <!-- строка для показа общей суммы оплат по этому заказу -->
                        <div class="row">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2   fontSizeMedium" >сумма всех оплат</div>
                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1   fontSizeMedium" data-sumPayments ></div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2  fontSizeMedium">добавить оплату</div>
                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 ">
                                <input type="text" size="4"  id="idModalWinValPayment" placeholder="сумма" value="0.00"/>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                <button class="btn btn-sm btn-primary" id="idModWinBtnAddPayment" name = 'sumMatZakaz'> внести сумму</button></div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2  text-center ">
                                <input id="idModalWinDatePayment" type="date"/>
                            </div>
                        </div>
                    </div>
                </div><!--modal-body-->
                <div class="modal-futer">
                </div>
            </div><!--modal content-->
        </div><!--modal-dialog-->
    </div><!--modal-fade -->

</div>
</body>
</html>
<script src="/js/viewAllPayments.js"></script>

