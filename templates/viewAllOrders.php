<?php
//можем здесь писать если просто вывод или пока что при подключении будет autoload.php в head.php
require '../autoload.php';
$filds_nameToView = [ 'nameOrder' =>'название заказа',
    'nameClient'=>'название клиента',
    'orderPrice'=>'цена',
    'isReady'=>'coстояние',
    'isCompleted'=>'комплект',
    'payment'=>'оплата',
'dateOrder'=>'дата'];
//функция показа в нужном виде данных в этом view (названия столбоц в thead,
// отображание в удобочитаемом виде готовности заказа 0-новый(не посчитан), 1-закрыт успешно, 2-закрыт неуспешно 3-новые (посчитан)
//$arrAll результат запроса из Class Order --->>>
// $query = "SELECT  o.id AS idOrder , o.nameOrder, c.name AS nameClient , o.orderPrice,o.isReady, o.isCompleted, SUM( p.sumPayment) AS payment
//FROM orders o, clients c, payments p
//                  WHERE o.idClient = c.id AND idOrder = p.idOrder AND o.id = p.idOrder
//                  GROUP BY idOrder ";

//в результате запроса получим 1)id заказа,2) название заказа,3) назавание заказчика,4) цена заказа, 5) степень готовности (0-новый не посчитан,1-закрыт успешно,2-закрыт неуспешно 3-новый посчитан),6) можно ли меняц цены заказа,
function showFromFields($idTable, $arrAll = [], $filds_nameToView){
        if (isset($arrAll) && !is_null($arrAll) && isset($arrAll[0])) {
//            из переданного асс массива для заголовков полей получим удобочитаемые пример 'name' =>'название заказа'
            $tableAll = '';
            //теперь в строку сверстаем таблицу
            $tableAll .= "<table id = '$idTable' , class='table-hover'>
            <thead><tr>
                      <td style = 'min-width: 130px'>$filds_nameToView[dateOrder]</td>
                      <td>$filds_nameToView[nameOrder]</td>
                      <td>$filds_nameToView[nameClient]</td>
                      <td>$filds_nameToView[orderPrice]</td>
                      <td>$filds_nameToView[isReady]</td>
                      <td>$filds_nameToView[isCompleted]</td>
                      <td>$filds_nameToView[payment]</td>
                      <td><a href='formOneOrder.php'>  <span class='glyphicon glyphicon-plus'></span> создать</a></td>
                  </tr>
            </thead>
            <tbody>";

            //проходим все строки таблицы
            foreach ($arrAll as $rowItem):
                //Дадим отображение для для $rowItem[isReady] вместо значения 0,1,2 дадим расшифровку <td>$rowItem[isReady]</td>
                $isReady='';
                //новый он не запущен в работу надо еще считать или утвердить цену
                // или получить задаток 70% и тогда первести в состояние запущен
                //
                if($rowItem[isReady] == 0 ){
                    $tableAll .= "<tr class='orderNewRow'>";
                    $isReady ="<td ><span class='orderNewCell'>новый</span></td>";
                }
                if($rowItem[isReady] == 3 ){
                    $tableAll .= "<tr class='orderNewRow'>";
                    $isReady ="<td ><span class='orderNewCell'>запущен</span></td>";
                }
                if($rowItem[isReady] == 1 ){
                    $tableAll .= "<tr class='orderSuccessRow'>";
                    $isReady = "<td> <span class='orderSuccessCell'>успешный</span></td>";
                }
                if($rowItem[isReady] == 2 ){
                    $tableAll .= "<tr class='orderNoSuccessRow'>";
                    $isReady ="<td><span class='orderNoSuccessCell'>провален</span></td>";
                }
                //Дадим отображение для для    $rowItem[isAllowCalculateCost] 1-разрешено, 0-не разрешено
                // <td>$rowItem[isAllowCalculateCost]</td>
                $isAllow='';
                if($rowItem[isCompleted] == 0){
                    $isAllow="<td class='text-center'><span class='orderNoSuccessCell'>нет</span></td>";
                }
                if($rowItem[isCompleted] == 1){
                    $isAllow="<td class='text-center'><span class='orderSuccesCell'>да</span></td>";
                }
                //для отображения предоплаты дадим расшифровку
                $payment='';
                if($rowItem[payment]==''|| $rowItem[payment]== 0)
                    $payment ="<td><span class='orderNoSuccessCell'>0</span></td>";
                else $payment ="<td><span class='orderSuccessCell'>$rowItem[payment]</span></td>";
                //для отображения полной оплаты
               
                $tableAll .= "
                                 <td>нач: $rowItem[dateBegin]<br>кон: $rowItem[dateEnd]</td>
                                 <td>$rowItem[nameOrder]</td>
                                 <td>$rowItem[nameClient]</td>
                                 <td>$rowItem[orderPrice]</td>
                                 $isReady
                                 $isAllow
                                 $payment
                                 <td ><a href='viewOneOrder.php?id=$rowItem[idOrder]' ><span class='glyphicon glyphicon-edit'> просмотр</span></a></td>
                                 <td ><button data-id=$rowItem[idOrder]><span class='glyphicon glyphicon-trash'></span></button></td>
                </tr>";
            endforeach;
            $tableAll .= '</tbody></table>';
            return $tableAll;
        }
        else{
            return'<div class="alert-info text-info text-center">пока нет данных</div>';
        }
}
?>
    <div class="container">
        <div class="row"><!-- строка поиска-->
                        <div class="col-lg-2">пустой див слева</div>
                        <div class="col-lg-10">
                            <div class="row">
<!--                                <form action="#" name="formSearchOrder">-->
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">первый див</div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"><input type="text" name="poiskZakazaName" placeholder="поиск по названию"></div>
                                        <div class="col-lg-offset-2 col-md-offset-2 col-sm-2 col-xs-2 "><input type="text" name="poiskZakazaFirma" placeholder="по фирме"></div>
<!--                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"><button name="btPoiskName" class="btn-primary">искать </button></div>-->
<!--                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"><button name="createOrder" class="btn-primary "><span class="glyphicon glyphicon-plus-sign"></button></div>-->
<!--                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"><a href="formOneOrder.php"><button class="btn btn-primary "><span class="glyphicon glyphicon-plus-sign"> </span></button></a> </div>-->


<!--                                </form>-->
                            </div>
                        </div>
        </div>
        <div class="row">
            <div class="col-lg-2 backForDiv">
                этот див слева от таблицы в нем можно расположить дополнительные кнопки добавить редактировать удалить
            </div>
            <div class="col-lg-10 backForDiv divForTable">
                <div class="col-lg-12" id="table_Orders">
                        <?php
                        //передадим id='tableOrder'; в отображение будущей таблицы
                          //echo \App\Models\Order::showAllFromTable('tableOrders',
                        //\App\Models\Order::findAll(),['id','nameOrder', 'idClient','orderPrice','isAllowCalculateCost']);
//                        var_dump(\App\Models\Order::selectForView());
                        //вызовем функцию отображения showFromFields($idTable, $arrAll = [], $filds_nameToView)
                        //$idTable = id уникальный таблицы,
                        // arrAll=[] массив данных передаваемых для отображения,
                        // $filds_nameToView ассоциативный массив полей для отображения определяется выше
                        echo showFromFields('tableOrders', \App\Models\Order::selectForView(),$filds_nameToView);
                        ?>

                    <script>
                        //вешаем клик на таблицу он должен вернуть id записи из кликнутой таблицы
                        $('#table_Orders').on('click',tableOrders, function () {
                           // здесь   $(this) - это sortStable
                            var target = event.target;//где был клик
                            if(target.tagName != 'TD' || target.closest('THEAD')) return;//клик в неинтересном месте для нас
                            //уберем подсветку во всей таблице кроме той строки в которой есть клик на ячейке
                            var idFromtrThisTD = $(target).parent().children()[0].textContent;
                            console.log('нашли id кликнутой строку '+ idFromtrThisTD  );
                            idFromtrThisTD = ( $(target).siblings() )

                        });
                        // функция выделения строки таблицы по клику на ней
                        //divId хранит id записи в выделенной строке( в кликнутой строке)
                        function findId(event) {
                            var target = event.target;//где был клик
                            if(target.tagName != 'TD' || target.closest('THEAD')) return;//клик в неинтересном месте для нас
                            //уберем подсветку во всей таблице кроме той строки в которой есть клик на ячейке
                            var idFromtrThisTD = $(target).parentNode.firstChild.textContent;
                            console.log('нашли id кликнутой строку '+ idFromtrThisTD  );

//                            $("table td[class ~= 'highLightTd']").removeClass('highLightTd');
//                            //подсветим ячейку где был клик и братьев ее, то есть выделим строку где был клик
//                            $(target).addClass('highLightTd').siblings().addClass('highLightTd');
                        }
                    </script>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 backForDiv">
                <div class="row">
                    <div class="col-lg-12"><button> показать все в заказе</button></div></div>
                    <div class="row">
                    <div class="col-lg-12">показать материалы к заказу</div></div>
                        <div class="row">
                        <div class="col-lg-12"></div></div>
                </div>
            </div>
            <div class="col-lg-10 backForDiv divForTable">
                <div class="row">
                    <div class="col-lg-6 text-center">название заказа </div>
                    <div class="col-lg-6 text-center">список материалов к заказу</div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <?php
                        echo \App\Models\MaterialsToOrder::showAllFromTable('tableMaterialsToOrder', \App\Models\MaterialsToOrder::findAll());
                        ?>
                    </div>
                </div>

            </div>
        </div>
        <div class="row"><!-- для отображения в виде таблицы 1 записи любой таблицы выбранной по id -->
            <div class="col-lg-12">
                <div class="row"><!-- заголовок записи-->
                    <div class="col-lg-12"> название записи </div>
                </div>
                <div class="row"><!-- сама запись в форме таблицы -->
                    <div class="col-lg-12">
                    <?php
                    //пробуем отобразить запись заказа с id = 1 ;
                    echo \App\Models\Order::showAllFromTable('tableOneOrderid_1' , \App\Models\Order::findObjByIdStatic(1) );

//                    echo('<br> запросим данные по заказу с id = 1 начало <br> ');
//                    var_dump(\App\Models\Order::findObjByIdStatic( 1 ));
//                    echo('<br> запросим данные по заказу с id = 1 конец <br> ');

                    ?>
                    </div>
                </div>

            </div>
        </div>

