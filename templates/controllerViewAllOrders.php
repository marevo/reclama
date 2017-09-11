<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 18.08.2017
 * Time: 23:32
 */
require '../autoload.php';

//пришел запрос на отправку в корзину заказа
if(isset($_POST['sendOrderToTrash'])){
    if(isset($_POST['idOrder'])){
        $idOrder = intval($_POST['idOrder']);
        $objOrder = \App\Models\Order::findObjByIdStatic($idOrder);
        $objOrder->isTrash = 1;
        $res = $objOrder->update();
        if(false != $res){
            echo "<script>$('.forDelClass').removeClass('forDelClass').parent().remove();</script>";
            showOnClientUspehWithText('заказ успешно отправлен в корзину');
        }else{
            showOnClientNeUspehWithText('не удалось отправить заказ в корзину');
        }
    }
}
//выкидывает на клиент отображение результата успешного запроса с определенным сообщением
function showOnClientUspehWithText(string $message){
    echo "<script>fUspehAll('$message');</script>";
}
//выкидывает на клиент отображение результата не успешного запроса с определенным сообщением
function showOnClientNeUspehWithText(string $message){
    echo "<script>fNoUspehAll('$message');</script>";
}
//запрос на отображение удаленных заказов
if(isset($_POST['showTrashedOrders'])){
    $arrAll = \App\Models\Order::getTrashedOrders();
    if(false != $arrAll){
        //проходим все строки таблицы
        foreach ($arrAll as $rowItem):
            //Дадим отображение для для $rowItem[isReady] вместо значения 0,1,2 дадим расшифровку <td>$rowItem[isReady]</td>
            $isReady='';
            //новый он не запущен в работу надо еще считать или утвердить цену
            // или получить задаток 70% и тогда первести в состояние запущен
            //
            if($rowItem[isReady] == 0 ){
                $tableAll .= '<tr class=\'orderNewRow orderIsTrashed\'>';
                $isReady ='<td ><span class=\'orderNewCell\'>новый</span></td>';
            }
            if($rowItem[isReady] == 3 ){
                $tableAll .= '<tr class=\'orderNewRow\'>';
                $isReady ='<td ><span class=\'orderNewCell\'>запущен</span></td>';
            }
            if($rowItem[isReady] == 1 ){
                $tableAll .= '<tr class=\'orderSuccessRow\'>';
                $isReady = '<td> <span class=\'orderSuccessCell\'>успешный</span></td>';
            }
            if($rowItem[isReady] == 2 ){
                $tableAll .= '<tr class=\'orderNoSuccessRow\'>';
                $isReady ='<td><span class=\'orderNoSuccessCell\'>провален</span></td>';
            }
            //Дадим отображение для для    $rowItem[isAllowCalculateCost] 1-разрешено, 0-не разрешено
            // <td>$rowItem[isAllowCalculateCost]</td>
            $isAllow='';
            if($rowItem[isCompleted] == 0){
                $isAllow="<td class='text-center'><span class='orderNoSuccessCell'>нет</span></td>";
            }
            if($rowItem[isCompleted] == 1){
                $isAllow='<td class=\'text-center\'><span class=\'orderSuccesCell\'>да</span></td>';
            }
            //для отображения предоплаты дадим расшифровку
            $payment='';
            if($rowItem[payment]==''|| $rowItem[payment]== 0)
                $payment ='<td><span class=\'orderNoSuccessCell\'>0</span></td>';
            else $payment ='<td><span class=\'orderSuccessCell\'>'.$rowItem[payment].'</span></td>';
            //для отображения полной оплаты

            $tableAll.='<td>нач:'.$rowItem[dateBegin].'<br/>кон:'. $rowItem[dateEnd].'</td><td>'
                .$rowItem[name].'</td><td>'
                .$rowItem[nameClient].'</td><td>'
                .$rowItem[orderPrice].'</td>'
                .$isReady.$isAllow.$payment.'<td><a href=\'viewOneOrder.php?id='.$rowItem[idOrder].'\' ><span class=\'glyphicon glyphicon-edit\'> просмотр</span></a></td><td></td></tr>';

        endforeach;
        //получили строки удаленных заказов для добавления в таблицу
//    echo "<script> $('tbody').append(\"".$tableAll."\");</script>";
        showOnClientUspehWithText('есть заказы в корзине');

        echo "<script>$('.orderIsTrashed').remove();$('tbody').html(\"".$tableAll."\"); </script>";
    }
    else{
        showOnClientNeUspehWithText('корзина пуста');
        $tableAll="<tr>нет результата</tr>";
        echo "<script>$('.orderIsTrashed').remove();$('tbody').html(\"".$tableAll."\"); </script>";
    }

}
//поиск заказа по подобию в названию клиента
if(isset($_POST['searchOrderForNameClient'])){
    if(isset($_POST['nameClient'])){
        $nameClientLike = htmlspecialchars($_POST['nameClient']);
        $ordersLikeNameClient = \App\Models\Order::getOrdersLikeNameClient($nameClientLike);
        if(false != $ordersLikeNameClient){
            //проходим все строки таблицы
            foreach ($ordersLikeNameClient as $rowItem):
                //Дадим отображение для для $rowItem[isReady] вместо значения 0,1,2 дадим расшифровку <td>$rowItem[isReady]</td>
                $isReady='';
                //новый он не запущен в работу надо еще считать или утвердить цену
                // или получить задаток 70% и тогда первести в состояние запущен
                //
                if($rowItem[isReady] == 0 ){
                    $tableAll .= '<tr class=\'orderNewRow orderLikeNameClient\'>';
                    $isReady ='<td ><span class=\'orderNewCell\'>новый</span></td>';
                }
                if($rowItem[isReady] == 3 ){
                    $tableAll .= '<tr class=\'orderNewRow orderLikeNameClient\'>';
                    $isReady ='<td ><span class=\'orderNewCell \'>запущен</span></td>';
                }
                if($rowItem[isReady] == 1 ){
                    $tableAll .= '<tr class=\'orderSuccessRow orderLikeNameClient\'>';
                    $isReady = '<td> <span class=\'orderSuccessCell\'>успешный</span></td>';
                }
                if($rowItem[isReady] == 2 ){
                    $tableAll .= '<tr class=\'orderNoSuccessRow orderLikeNameClient\'>';
                    $isReady ='<td><span class=\'orderNoSuccessCell\'>провален</span></td>';
                }
                //Дадим отображение для для    $rowItem[isAllowCalculateCost] 1-разрешено, 0-не разрешено
                // <td>$rowItem[isAllowCalculateCost]</td>
                $isAllow='';
                if($rowItem[isCompleted] == 0){
                    $isAllow="<td class='text-center'><span class='orderNoSuccessCell'>нет</span></td>";
                }
                if($rowItem[isCompleted] == 1){
                    $isAllow='<td class=\'text-center\'><span class=\'orderSuccesCell\'>да</span></td>';
                }
                //для отображения предоплаты дадим расшифровку
                $payment='';
                if($rowItem[payment]==''|| $rowItem[payment]== 0)
                    $payment ='<td><span class=\'orderNoSuccessCell\'>0</span></td>';
                else $payment ='<td><span class=\'orderSuccessCell\'>'.$rowItem[payment].'</span></td>';
                //для отображения удален $rowItem[isTrash]=1 - удален 0 - нет
                if($rowItem[isTrash] == 0)
                    $isTrash='<button data-id='.$rowItem[idOrder].'><span class=\'glyphicon glyphicon-trash\'></span></button>';
                else $isTrash ='';
                $tableAll.='<td>нач:'.$rowItem[dateBegin].'<br/>кон:'. $rowItem[dateEnd].'</td><td>'
                    .$rowItem[name].'</td><td>'
                    .$rowItem[nameClient].'</td><td>'
                    .$rowItem[orderPrice].'</td>'
                    .$isReady.$isAllow.$payment.'<td><a href=\'viewOneOrder.php?id='.$rowItem[idOrder].'\' ><span class=\'glyphicon glyphicon-edit\'> просмотр</span></a></td>'
                .'<td>'.$isTrash.'</td></tr>';

            endforeach;
            echo "<script>$('.orderLikeNameClient').remove();$('tbody').html(\"".$tableAll."\"); </script>";
            showOnClientUspehWithText('заказы найдены');

            echo "<script>'заказы найдены'</script>";
        }
        else {
            $tableAll='нет результата';
            echo "<script>$('.orderLikeNameClient').remove();$('tbody').html(\"".$tableAll."\"); </script>";
            showOnClientNeUspehWithText('заказы не найдены');
        }
    }
    
}
//поиск по подобию названия заказа
if(isset($_POST['searchOrderForName'])){
    if(isset($_POST['nameOrder'])){
        $nameOrderLike = htmlspecialchars($_POST['nameOrder']);
        $ordersLikeName = \App\Models\Order::getOrdersLikeName($nameOrderLike);
        if(false != $ordersLikeName){
            //проходим все строки таблицы
            foreach ($ordersLikeName as $rowItem):
                //Дадим отображение для для $rowItem[isReady] вместо значения 0,1,2 дадим расшифровку <td>$rowItem[isReady]</td>
                $isReady='';
                //новый он не запущен в работу надо еще считать или утвердить цену
                // или получить задаток 70% и тогда первести в состояние запущен
                //
                if($rowItem[isReady] == 0 ){
                    $tableAll .= '<tr class=\'orderNewRow orderLikeName\'>';
                    $isReady ='<td ><span class=\'orderNewCell\'>новый</span></td>';
                }
                if($rowItem[isReady] == 3 ){
                    $tableAll .= '<tr class=\'orderNewRow orderLikeName\'>';
                    $isReady ='<td ><span class=\'orderNewCell \'>запущен</span></td>';
                }
                if($rowItem[isReady] == 1 ){
                    $tableAll .= '<tr class=\'orderSuccessRow orderLikeName\'>';
                    $isReady = '<td> <span class=\'orderSuccessCell\'>успешный</span></td>';
                }
                if($rowItem[isReady] == 2 ){
                    $tableAll .= '<tr class=\'orderNoSuccessRow orderLikeName\'>';
                    $isReady ='<td><span class=\'orderNoSuccessCell\'>провален</span></td>';
                }
                //Дадим отображение для для    $rowItem[isAllowCalculateCost] 1-разрешено, 0-не разрешено
                // <td>$rowItem[isAllowCalculateCost]</td>
                $isAllow='';
                if($rowItem[isCompleted] == 0){
                    $isAllow="<td class='text-center'><span class='orderNoSuccessCell'>нет</span></td>";
                }
                if($rowItem[isCompleted] == 1){
                    $isAllow='<td class=\'text-center\'><span class=\'orderSuccesCell\'>да</span></td>';
                }
                //для отображения предоплаты дадим расшифровку
                $payment='';
                if($rowItem[payment]==''|| $rowItem[payment]== 0)
                    $payment ='<td><span class=\'orderNoSuccessCell\'>0</span></td>';
                else $payment ='<td><span class=\'orderSuccessCell\'>'.$rowItem[payment].'</span></td>';
                //для отображения удален $rowItem[isTrash]=1 - удален 0 - нет
                if($rowItem[isTrash] == 0)
                    $isTrash='<button data-id='.$rowItem[idOrder].'><span class=\'glyphicon glyphicon-trash\'></span></button>';
                else $isTrash ='';
                $tableAll.='<td>нач:'.$rowItem[dateBegin].'<br/>кон:'. $rowItem[dateEnd].'</td><td>'
                    .$rowItem[name].'</td><td>'
                    .$rowItem[nameClient].'</td><td>'
                    .$rowItem[orderPrice].'</td>'
                    .$isReady.$isAllow.$payment.'<td><a href=\'viewOneOrder.php?id='.$rowItem[idOrder].'\' ><span class=\'glyphicon glyphicon-edit\'> просмотр</span></a></td>'
                    .'<td>'.$isTrash.'</td></tr>';

            endforeach;
            echo "<script>$('.orderLikeName').remove();$('tbody').html(\"".$tableAll."\"); </script>";
            showOnClientUspehWithText('заказы найдены');
        }
        else {
            $tableAll='нет результата';
            echo "<script>$('.orderLikeName').remove();$('tbody').html(\"".$tableAll."\"); </script>";
            showOnClientNeUspehWithText('заказы не найдены');
        }
    }
}

//поиск по подобию