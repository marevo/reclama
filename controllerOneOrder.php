<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 05.07.2017
 * Time: 16:12
 */
namespace App;
use App\Models\Order;
//use App\ModelLikeTable; //не забыть удалить

require ('autoload.php');
//use App\ModelLikeTable;
//addSum добавление суммы оплаты по заказу
if(isset($_GET['addSum'])){
    if(isset($_GET['sum']))
        $sum = htmlspecialchars($_GET['sum']);
    if(isset($_GET['idOrder']))
        $idOrder = intval($_GET['idOrder']);
    if(isset($_GET['idClient']))
        $idClient = intval($_GET['idClient']);
    if(isset($_GET['dateToday']))
        $dateUpdateSum = htmlspecialchars($_GET['dateToday']);
//    echo "idOrder = $idOrder, idClient = $idClient sum = $sum  dateToday = $dateUpdateSum";
//die();//не забыть удалить
$payment = new \App\Models\Payment();
    $payment->idOrder = $idOrder;
    $payment->idClient = $idClient;
    $payment->date = $dateUpdateSum;
    $payment->sumPayment = $sum;
    $resInsert = $payment->insert();
    //после пробы добавить оплату мы должны зановосчитать оплату по idOrder
    $payment =  \App\Models\Payment::showSumAllPayments($idOrder);

    if($resInsert == false ){
        //не удалось добавить оплату
        echo "$payment <script>fNoUspeh();</script>";
//        echo "$('#sumAllPayments').before('<div class=' alert alert-info '>не удалось добавить оплату $sum</div>')";//не забыть удалить
    }
    else{
//        удалось добавить оплату
        echo "$payment 
<script>
fUspeh();
</script>
        ";
    }
}
//внесение изменений в поля заказа вызывает ф
if(isset($_GET['update'])){
    if(isset($_GET['idOrder']))
        $idOrder = intval($_GET['idOrder']);
    if(isset($_GET['nameField']))
        $nameField = htmlspecialchars($_GET['nameField']);
    if(isset($_GET['valueField']))
        $valueField = htmlspecialchars($_GET['valueField']);
    switch ($nameField){
        ///название заказа
        case 'nameOrder':
            $or = Order::findObjByIdForViewOneOrder($idOrder);
            $or->nameOrder = $valueField;
            $res = $or->update();
            //найдем в базе этот заказ и передадим его название старое или новое неважно
            $or = Order::findObjByIdForViewOneOrder($idOrder);
            echo "$or->nameOrder 
            <script>
            ORDER['$nameField'] = '$or->nameOrder';
            </script>
            ";
            //запустим функцию отображения ответа сервера (если успешно поменяли/ или если не успешно поменяли)
            if($res == true){
                echo"<script>fUspeh();</script>";
            }
            if($res == false){
                echo"<script>fNoUspeh();</script>";
            }
            break;
        //описание заказа
        case 'descriptionOrder':
            $or = Order::findObjByIdForViewOneOrder($idOrder);
            $or->descriptionOrder = $valueField;
            $res = $or->update();
            //найдем в базе этот заказ и передадим его название старое или новое неважно
            $or = Order::findObjByIdForViewOneOrder($idOrder);
            echo "$or->descriptionOrder 
            <script>
            ORDER['$nameField'] =' $or->descriptionOrder';
            </script>
            ";
            //запустим функцию отображения ответа сервера (если успешно поменяли/ или если не успешно поменяли)
            if($res == true){
                echo"<script>fUspeh();</script>";
            }
            if($res == false){
                echo"<script>fNoUspeh();</script>";
            }
            break;
//        название клиента
        case 'nameClient':

            break;
        default:
            echo"<script>fNoUspeh();</script>";
            break;
    }
//die();
//   echo '<script>
//
// $("#newTargetForControlInput").val( \'{ "rez":"true","name":"'.$nameField.'","value":"'.$valueField.'" }\');
// $("#newTargetForControlInput").click();
//
//</script>';
//    echo"
//<script>
//$('#newTargetForControl').attr('data-from', '{rez:true,{name:$nameField,value:$valueField} }');
//$('#newTargetForControl').text('{rez:true,{name:$nameField,value:$valueField} }');
//$('#newTargetForControlInput').val('{rez:true,{name:$nameField,value:$valueField} }');
//changeDataFrom.doit();
//
//</script>
//";
//    echo 'пришел запрос на апдейте';
//    echo "<script>$('#newTargetForControl').change();</script>";
}