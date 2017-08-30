<?php
require '../autoload.php';

$IDORDER = 0;
if(isset($_GET['id'])){
//    если передали id значит работаем с ним иначе будем брать в else по умолчанию id=1
    $IDORDER = $_GET['id'];
}
else{
    $IDORDER = 1;
}

$or = new \App\Models\Order();//для вывода полей(чтобы наугад через стрелку не бить
$order = \App\Models\Order::findObjByIdStatic($IDORDER);//метод нахождения заказа по id=1 id должны передать при вызове этого viewOneOrder.php
//найдем id клиента по заказу, чтобы из таблицы клиенты найти его имя
//$idClient = $order->idClient;
//запрос в таблицу клиентов с передачей ранее найденного idClent , чтобы взять имя клиента
//$client = \App\Models\Client::findObjByIdStatic($idClient)[0];
$nameClient = $order->getNameClient();
//$payment сумма всех оплат по заказу с id=$IDORDER
$payment =  \App\Models\Payment::showSumAllPayments($IDORDER);
//функция отображения источника заказа в зависимости от цифры в базе
function fSource(int $id){
    //    <!--  0-не известен, 1-входящий звонок, 2-prom.ua, 3-olx, 4-сайт, 5 реклама в газете -->
    if($id == 0)
        return 'не известно';
    if($id == 1)
        return 'входящий звонок';
    if($id == 2)
        return 'prom.ua';
    if($id == 3)
        return 'olx';
    if($id == 4)
        return 'сайт';
    if($id == 5)
        return 'реклама в газете (название газеты)';
    if($id == 6)
        return 'другой';
}
/**
* @param $or
**/
function fIsCompleet($or)
{
  if ($or->isCompleted == 0)
      return'<td class="orderNoSuccessCell" data-name="isCompleted"> не укомплектован</td>';
  else
      return '<td class="orderSuccessCell" data-name="isCompleted">да укомплектован</td>';
}
// степень готовности заказа 0-новый, 1-закрыт успешно, 2-закрыт неуспешно
function fIsReady(int $isReady){
    if($isReady ==0)
        return "<tr><td >статус заказа</td><td class='orderNoSuccessCell' data-name='isReady'>новый</td></tr>";
    if($isReady ==3)
        return "<tr><td >статус готовности</td><td class='orderSuccessCell' data-name='isReady'>в работе</td></tr>";
    if($isReady ==1)
        return "<tr><td>статус готовносии</td><td class='orderSuccessCell' data-name='isReady'>закрыт успешно </td></tr>";
    if($isReady ==2)
        return "<tr><td >статус готовности</td><td class='orderNoSuccessCell' data-name='isReady'>закрыт не успешно </td></tr>";
}
function fIsInstall(int $isInstall){
    //установлен у клиента 0-нет, 1-в процессе, 2-установлен
    if($isInstall == 0)
        return "<tr><td >статус установки</td><td class='orderNoSuccessCell' data-name='isInstall'>не установлен </td></tr>";
    if($isInstall == 2)
        return "<tr><td>статус установки</td><td class='orderSuccessCell' data-name='isInstall'>установлен успешно </td></tr>";
    if($isInstall == 1)
        return "<tr><td>статус установки</td><td class='orderNoSuccessCell' data-name='isInstall'>в процессе установки </td></tr>";
}

?>
<!DOCTYPE HTML>
<html>
<title> просмотр данных заказа </title>
<?php include ('../head.php')?>
<body>
<div class="container">
    <div class="row">*header*
        <?php require_once('header.php'); ?>
    </div>
    <div class="row"><!-- навигация -->
        <?php include ('../navigation.php');?>
    </div>
    <div class="row">
        <!--рабочее место слева для будущего меню-->
        <div class="col-lg-2 backForDiv">
            этот див слева от таблицы в нем можно расположить дополнительные кнопки добавить редактировать удалить
        </div>
        <!--/рабочее место слева для будущего меню-->

        <!--рабочее место справа-->
        <div class="col-lg-10 backForDiv divForTable">
            <?php echo //передаем объект ORDER для распределения
            " <script>
 var ORDER = {
     id : '$order->id',
     descriptionOrder: '$order->descriptionOrder',
     nameOrder : '$order->nameOrder',
     source : '$order->source',
     idClient : '$order->idClient',
     nameClient : '$nameClient',
     orderPrice : '$order->orderPrice',
     manufacturingPrice : '$order->manufacturingPrice',
     isCompleted : '$order->isCompleted',
     isReady : '$order->isReady',
     isInstall : '$order->isInstall',
     dateOfOrdering : '$order->dateOfOrdering',
     dateOfComplation : '$order->dateOfComplation',
     isAllowCalculateCost : '$order->isAllowCalculateCost',
     isTrash : '$order->isTrash',
     sumAllPayments : '$payment'
 };
             </script>";
            ?>
            <div class="row"><!--просмотр одного заказа-->
                <div class="col-lg-12 bg-primary panel-info h3 " data-name="nameOrder">
<!--                    --><?php //echo $order->nameOrder; ?><!-- для --><?php //echo $nameClient; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-4">сегодня <span id = 'dateToday'></span>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-8 col-xs-8" id="rezZaprosaKServer" >
                        <div class=" uspeh text-center "><span class="glyphicon glyphicon-import "> успешно</span></div>
                        <div class=" noUspeh text-center "><span class="glyphicon glyphicon-alert "> ошибка обратитесь к разработчику</span></div>
                </div>
                <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12" id="rezShow"> что пришло с сервера</div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-7">
<table id="tableOneOrder" data-idorder=""><thead><tr><td>данные заказа</td>
        <td>значение</td>
        <td><span class="glyphicon glyphicon-trash"></span> пометить</td></thead>
    <tbody>
    <tr><td>название заказа</td><td data-name="nameOrder"><?php  echo $order->nameOrder;?></td><td><span class="glyphicon glyphicon-edit"> править</span></td></tr>
    <!--  0-не известен, 1-входящий звонок, 2-prom.ua, 3-olx, 4-сайт, 5 реклама в газете -->
    <tr>
        <td>описание заказа</td><td data-name="descriptionOrder"><?php echo $order->descriptionOrder; ?></td></tr>
    <tr><td>источник</td><td data-name="source">
             <?php //echo fSource($order->source); ?>
        </td></tr>
    <tr><td>название клиента</td><td data-name="nameClient">
<!--            --><?php // echo  $nameClient;?>
        </td></tr>
    <tr><td>цена</td><td data-name="orderPrice">
<!--            --><?php // echo $order->orderPrice;?>
        </td></tr>
    <tr><td>цена комплектующих</td><td data-name="manufacturingPrice">
<!--            --><?php // echo $order->manufacturingPrice;?>
        </td></tr>
    <tr><td>комплектация</td><td data-name="isCompleted" >
            <!--        --><?php // echo fIsCompleet($order);?>
        </td></tr>
    <tr><td> статус </td><td data-name='isReady'>
            <?php //функция вернет строку сразу статуса заказа
            //     echo fIsReady($order->isReady); ?>
        </td></tr>
    <tr><td>установлен</td><td data-name='isInstall'>
            <?php //верноет строку таблицы отображение установлен или нет заказ у клиента(повесили ли вывеску, прикрепили ли баннер)
            //    echo fIsInstall($order->isInstall);?>
        </td></tr>
    <tr><td title="сколько уже проплатил клиент">оплата</td><td data-name="sumAllPayments" id="sumAllPayments" >
<!--            --><?php //echo $order->getSumAllPayments(); ?>
        </td>
        <!-- внести оплату -->
<!--        <td onclick="fAddPayment(event);" data-name="sumAllPayments"><span class="glyphicon glyphicon-plus">внести оплату</span></td>-->
        <td id="addPayment" data-name ='addPayment'><span class="glyphicon glyphicon-plus">внести оплату</span></td>
    </tr>
    <!--// внести оплату -->
     <tr><td title='нач: дата начала заказа'>дата нач </td><td data-name="dateOfOrdering">
<!--             --><?php //echo $order->dateOfOrdering; ?>
         </td></tr>
    <tr><td title='дата завершения заказа'>дата кон </td><td data-name="dateOfComplation">
<!--        --><?php //echo $order->dateOfComplation; ?>
    </td></tr>
    <tr><td title='разрешение на изменение цены комплектующих (если они изменялись )'>разрешить пересчет цены комплекующих</td>
        <td data-name="isAllowCalculateCost">
<!--            --><?php //if($order->isAllowCalculateCost==0) echo 'нельзя';else echo 'можно';  ?>
        </td></tr>
    <tr><td title='удаление заказа поставим метку что заказ в корзине'>отправить в корзину заказ</td>
        <td data-name="isTrash">
<!--            --><?php //if($order->isTrash==0) echo 'не удален';else echo 'в корзине';  ?>
        </td></tr>
<script>
    //*на событие загрузки таблицы добавили на саму таблицу dbclicl функцию al
    var svetofor = 'green';
    $('#tableOneOrder').ready(function() {
        $('#tableOneOrder tbody ').on('click' ,function(event) {
            var target = event.target;
            //event.stopImmediatePropagation(); event.preventDefault();   event.stopPropagation();
            console.log('event.key' + event.key);
            //пришлоо событие отказа от ввода суммы оплаты
            if(target.nodeName=='INPUT' && target.name == 'inSumNo'){
                console.log('передумал вводить сумму');//не забыть удалить
                // опять установим клавишу для вызова поля добавления
                $('#addPayment').html('<span class="glyphicon glyphicon-plus"></span>'); //не забыть
                //включим зеленый свет светофора(может быть вызвано опять поле добавки оплаты)
                svetofor = 'green';
                return false;
            }
            if(target.nodeName == 'TR' )
                return ;
            //обработчик клика в среднем столбце таблицы
            if(target.name == 'submitInputForm' || target.name == 'noSubmitInputForm'){
                console.log('сработала одна из кнопок отправить/передумал в данном случае :'+ $(target).val() );{}
//  вызываем функцию обработки этих кнопок по ним или отправим или не отправми на сервер запрос об изменениях
                zaprosInput();
                return false;
            }

            if(target.nodeName != 'TD'){
                while (target.nodeName != 'TD'){
                    target=target.parentNode;
                }
            }
            //нашли td где кликнули target -> <td >

            if($(target).attr('data-name') == 'addPayment'){
                var tdWhereWasClick = target ;//td где был клик
                console.log ('зашли в обработку клика добавления оплаты надо сразу выйти из обработки клика');
                //вызовем функцию по обработке клика на добавление платежа
                if(svetofor &&  svetofor == 'green'){
                    fAddPayment(tdWhereWasClick,event,svetofor);//не забыть удалить
                }
            }

            var numberCellInTr = $(tdWhereWasClick).index();
            if(numberCellInTr != 2 )
                return false;

            console.log('click : numberCellInTr кликнули в '+ numberCellInTr + 'столбце' );
            var trWhereWasClick;
            while(target.nodeName != 'TR' ){
                target = target.parentNode;
            }
            trWhereWasClick = target;
        })
    });
    $('#tableOneOrder').ready(function() {
        $('#tableOneOrder tbody ').on('dblclick' ,function(event) {
            var target = event.target;
            $(target).addClass('forClear');
//event.stopImmediatePropagation(); event.preventDefault();   event.stopPropagation();
            if(target.nodeName == 'TR')
                return;
            if(target.nodeName != 'TD'){
                while (target.nodeName != 'TD'){
                    target=target.parentNode;
                }
            }
            //нашли td где кликнули
            var tdWhereWasClick = target;

            var numberCellInTr = $(tdWhereWasClick).index();
            if(numberCellInTr != 1 )
                return false;
            $(tdWhereWasClick).attr('id','forClear');
//            вызовем функцию для обработки двойного клика в средней ячейке таблицы где написаны значения полей заказа
            var fildName = $(target).attr('data-name');//узнали назавние поля для которого будем делать update
            ORDER.nameFieldForUpdate = fildName;
            ORDER.oldValue = ORDER[fildName];
            console.log('название поля: '+ ORDER.nameFieldForUpdate +
                ' значение поля для Update '+ ORDER["nameFieldForUpdate"] + '  старое значение поля: '+ ORDER.oldValue    );
            //если есть поле с таким именем то вызовем функцию для добавления input нужного вида в зависимости от поля
            if(fildName != 'nameClient'){//не можем менять название клиента здесь это надо делать только в карточке клиента
               //вставка в DOM поля для изменения с кнопками #вставка в DOM
                if(ORDER.nameFieldForUpdate !=''){
                    $(tdWhereWasClick).html(inputForm(ORDER.nameFieldForUpdate));
                }
            }
            else {
                $('#forClear').removeClass('forClear').removeAttr('id');
                ORDER.nameFieldForUpdate='';
                ORDER.oldValue ='';
            }

//            forUpdate(tdWhereWasClick);// не забыть удалить эта функция лишняя надо бы удалить

            //остановим событие чтобы дальше не всплыло
            event.stopImmediatePropagation();
            return false;
        })
    });
//на загрузку страницы добавим запуск функции по распределению полей объекта ORDER по местам страницы
    $('#tableOneOrder').ready(function(){
        //console.log('таблица загружена запустим расстановку значений ORDER согласно атрибута data-name');
        $('ORDER').ready(function (){
           console.log('загружен объкте ORDER');
            //запустим функцию расстановки по местам полей объкта ORDER
            allocateOrderField(ORDER);
        });
    });
//*функция "распределения по местам" на странице данных объекта ORDER
    /**
     *функция "распределения по местам" на странице данных объекта ORDER
     */
    function allocateOrderField() {
        var i=0;
        for  (var k in ORDER){
            if(ORDER[k]!= 'undefined' ){
                var tdToAllocate = $('[data-name = '+ k +']');
                tdToAllocate.text ( ORDER[k] ) ;
                //                console.log(k+':'+ORDER[k]);

                if(k =='isReady'){
                    switch (ORDER[k]){
                        case '0' :
                            tdToAllocate.addClass('orderNoSuccessCell').text('новый');
                            break;
                        case '3' :
                            tdToAllocate.addClass('orderNoSuccessCell').text('в работе');
                            break;
                        case '1' :
                            tdToAllocate.addClass('orderSuccessCell').text('закрыт успешно');
                            break;
                        case '2' :
                            tdToAllocate.addClass('orderSuccessCell').text('закрыт не успешно');
                            break;
                    }
                }
                if(k == 'isInstall'){
                    switch (ORDER[k]){
                        case '0':
                            tdToAllocate.addClass('orderNoSuccessCell').text('не установлен');
                            break;
                        case '1':
                            tdToAllocate.addClass('orderNoSuccessCell').text('в процессе установки');
                            break;
                        case '2':
                            tdToAllocate.addClass('orderSuccessCell').text('установлен успешно');
                            break;
                    }
                }
                if(k == 'isCompleted'){
                    switch (ORDER[k]){
                        case '0':
                            tdToAllocate.addClass('orderNoSuccessCell').text('нет').attr('data-value',ORDER[k]);
                            break;
                        case '1':
                            tdToAllocate.addClass('orderSuccessCell').text('да').attr('data-value',ORDER[k]);
                            break;
                    }
                }
                if(k == 'source'){
                    switch (ORDER[k]){
                        case  '0':
                            tdToAllocate.text('не известно').attr('data-value',ORDER[k]);
                            break;
                        case  '1':
                            tdToAllocate.text('входящий звонок').attr('data-value',ORDER[k]);
                            break;
                        case  '2':
                            tdToAllocate.text('prom.ua').attr('data-value',ORDER[k]);
                            break;
                        case  '4':
                            tdToAllocate.text('сайт').attr('data-value',ORDER[k]);
                            break;
                        case  '3':
                            tdToAllocate.text('olx').attr('data-value',ORDER[k]);
                            break;
                        case  '5':
                            tdToAllocate.text('реклама в газете (название газеты)').attr('data-value',ORDER[k]);
                            break;
                        case  '6':
                            tdToAllocate.text('другое').attr('data-value',ORDER[k]);
                            break;
                    }
                }
                if(k == 'isAllowCalculateCost') {
                    switch (ORDER[k]) {
                        case '0':
                            tdToAllocate.text('нельзя');
                            break;
                        case '1':
                            tdToAllocate.text('можно');
                            break;
                    }
                }
                if(k== 'isTrash'){
                    switch (ORDER[k]) {
                        case '0':
                            tdToAllocate.text('не удален');
                            break;
                        case '1':
                            tdToAllocate.text('в корзине');
                            break;
                    }
                }
            }
            else
                console.log('i='+ ++i +' -> ' +k+':'+ORDER[k]);
        }//конец for in
    }//конец функции
    //*/на событие загрузки таблицы добавили на саму таблицу dbclicl функцию al
    //* по загрузке newTargetForControl повесим на него onchange
    $('#newTargetForControlInput').ready(function() {
        $('#newTargetForControlInput').on('click', fRezChange);
//        $('#newTargetForControlInput').on('input', function () {
//            console.log('сработал change');
//        });
    });
    //*/ по загрузке newTargetForControlInput повесим на него onchange
    //* функция отработки ответа сервера при запросе изменения полей
    function fRezChange(){
        console.log('fRezChange input сработал на paste вставили текст '+ $('#newTargetForControlInput').val() );
//        сразу после вставки очистили поле
//        $('#newTargetForControlInput').val('');
//        $('#rezShow').text($('#newTargetForControl input').val());
    }
    //*/ функция отработки ответа сервера при запросе изменения полей

    //id заказа который будем показывать на этой viewOneOrder.php
    var idOrder,idClient, oldSum, dateToday;
    <?php if($IDORDER !=null) {echo
    "idOrder =  $IDORDER;
    $('#tableOneOrder').attr('data-idOrder','$IDORDER').attr('data-oldSum','$payment');
    oldSum = '$payment';" ;
    }
    ?>
    //при загрузке страницы в '#dateToday' будет выведена сегодняшняя дата в формате yyyy-mm-dd
    document.addEventListener('DOMContentLoaded',function () {
        var date = getDate();
        $('#dateToday').html(date)  ;
        dateToday = date;
        idClient = $('#idClient').html();
    } );
    console.log('tableOneOrder idOrder '+ idOrder);
    console.log('tableOneOrder oldSum '+ oldSum);
    //*функция обработки dblclick которая будет вызываться для отправки запроса на update
    function forUpdate(target) {
        var t = target;
        var fildName = $(t).attr('data-name');//узнали назавние поля для которого будем делать update
        ORDER.nameFieldForUpdate = fildName;
        ORDER.oldValue = ORDER[fildName];
        console.log('название поля: '+ ORDER.nameFieldForUpdate +
            ' значение поля для Update '+ ORDER["nameFieldForUpdate"] + '  старое значение поля: '+ ORDER.oldValue    );
        //если есть поле с таким именем то вызовем функцию для добавления input нужного вида в зависимости от поля
        if(ORDER.nameFieldForUpdate !=''){
            $(t).html(inputForm(ORDER.nameFieldForUpdate));
        }

    }


    //*/функция вставки в изменяемое поле форму с кнопками
    //напишем функцию, что вернет нужный form с нужным input в зависимости от типа поля nameField для вставки его в наш кликнутый td
    function inputForm(nameField) {
        var htmString;//будущее поле с кнопками отправить и передумал

        var inputt;
        var submitt ="<br/><input   class='button  btn-group-vertical btn-primary' type='button' name='submitInputForm'   value = 'изменить' />";
        var nosubmitt ="<input class='button btn-group-vertical btn-info' type='button' name='noSubmitInputForm'  value = 'передумал' />";
        var pat = " ";
        if(nameField == 'nameOrder' || nameField == 'descriptionOrder'  ) {
            inputt = "<textarea id='idInputForm' name='" + nameField + "' value='" + ORDER.oldValue +
                "' autofocus cols='30' rows='4'>"+ORDER.oldValue+"</textarea>";
        }
        if(nameField == 'source'){
            //#работаю здесь надо выставить источник таким же как он есть в отображении 
            inputt ="<input type='radio' name='source' value='0' checked/>не известен<br>"+
                "<input type='radio' name='source' value='1'/>входящий звонок<br>"+
                "<input type='radio' name='source' value='2'/>prom.ua<br>"+
                "<input type='radio' name='source' value='3'/>olx<br>"+
                "<input type='radio' name='source' value='4'/>сайте<br>"+
                "<input type='radio' name='source' value='5'/>объявление в газете";
        }

        htmString = inputt + submitt + nosubmitt;
        return htmString;
    }
    //*/функция вставки в изменяемое поле форму с кнопками
    //*функция обработки запроса к кнопкам изменить и передумал
    function zaprosInput() {
        var target = event.target;
        ORDER.newValue = $.trim($('#idInputForm').val());
        if(target.name =='submitInputForm' && ORDER.newValue != ORDER.oldValue){
          console.log('на сервер отправим '+ORDER.nameFieldForUpdate + " : "+ORDER.newValue);
            jquery_send('#forClear','get','../controllerOneOrder.php',
                ['update','nameField','valueField','idOrder'],
                ['',ORDER.nameFieldForUpdate,ORDER.newValue,ORDER.id]);
            ORDER.nameFieldForUpdate ='';
            ORDER.oldValue = '';
            ORDER.newValue = '';
        }
        if(target.name =='noSubmitInputForm'){
            console.log(' зашли в кнопку я передумал не будем отправлять на сервер');
            //передумали вносить изменения и не отправляем форму надо вернуть значение то что было в кликнутой ячейке
            $('#forClear').html( ORDER[ORDER.nameFieldForUpdate]).removeClass('forClear').removeAttr('id');;
            ORDER.nameFieldForUpdate ='';
            ORDER.oldValue = '';
            ORDER.newValue = '';
        }
    }
    //*/функция обработки запроса к кнопкам изменить и передумал

    //*/функция которая будет вызываться для отправки запроса на update

    function fAddPayment(tdWhereWasClick, event) {
        svetofor = 'red';
        //где кликнули var tdWhereWasClick
//        если есть уже input то не будем добавлять еще, а выйдем
            console.log('зашли в ф fAddPayment она добовит поле input вместо <<+ оплатить>>');
            //соседняя ячейка var newTarget слева будет обозначена для вывода туда результата добавки оплаты
            var newTarget = $(tdWhereWasClick).siblings()[1];
//            $(newTarget).css('border', 'solid 3px red');

//        var trParent = $(newTarget).parent();//вышли на tr и там в первом дочернем <td> будем вводить сумму добавки к платежу //не забыть удалить
//        console.log('номер ячеки куда вставим результа update '+ 1); //не забыть удалить
            //определим в input для вставки вместо старой суммы
            var inputToAddPayments = "" +
                "<input id='inSum' name='addSum' type='text' value='0.00'  "+
                " pattern='\\d{1,7}(\.|,)\\d{2}' autofocus />"+
                "<input name='inSumNo' class='button btn-success' type = 'button' value='передумал' />" +
//                "<input name='submitInput' style='display: none' value = 'пришла сумма платежа по заказу' />" +
                "";
            $(tdWhereWasClick).html(inputToAddPayments);
//        сразу вешаем событие на клик в нашем новом input
//            $('#inSum').on('input',function (event) {//почему input не работает, а keyup работает
            $('#inSum').on('keyup',function (event) {
                var target = event.target;
                if(target.nodeName == 'INPUT'){
                    var sum = $('#inSum').val();
                    if( event.keyCode == 13 && sum !=0 && sum !='' ){
                        //отправим сумму оплаты
                        jquery_send('#sumAllPayments','get','../controllerOneOrder.php',
                            ['addSum','idOrder','idClient','sum','dateToday'],
                            ['', ORDER.id,ORDER.idClient, sum, getDate()]);
                        $('#addPayment').html('<span class="glyphicon glyphicon-plus"></span>'); //не забыть
                        //включим зеленый свет светофора(может быть вызвано опять поле добавки оплаты)
                        svetofor = 'green';
                    }
                    else {
                        console.log('не отправили на сервер event.keyCode == '+ event.keyCode +' sum '+sum );

                        //переменить ORDER (выслать сюда из контроллера ORDER!
//                        return false;
//                        console.log('но это был !ВНИМАНИЕ не  ентер в поле ввода стр 200  кнопка '+ event.keyCode);
//                вызовем function обработки ввода в поле добавки
//                        fupdateAllsumPayment(); //в строке 500 не забыть удалить эту строку и саму функцию
                    }
                    return false;//не забыть удалить

                }
            });
    }
    //функция обработки input fupdateAllsumPayment(ввод клавиши энтер в поле для ввода суммы оплаты)
    function fupdateAllsumPayment(event) { //вызов идет в 200 стр
        var val = $('#inSum').val();
        console.log('считали сумму для внесения платежа val:'+val);
//        return false;//не забыть удалить
        if(val !='' || val!= 0){
            if(event.keyCode == 13 ){
                var sumForUpdate = $('#inSum').val();
                console.log('нажали enter в поле input ввели: ' + sumForUpdate);
                return false;
                if(sumForUpdate != 0 || sumForUpdate !=''  ){
                    jquery_send('#sumAllPayments','get','../controllerOneOrder.php',
                        ['addSum','sum','idOrder','idClient','dateToday'],['',sumForUpdate,idOrder, idClient ,dateToday ]);
//                    alertSuccess();

                    function alertNoSuccess() {
                        $('#sumAllPayments').before('<div class=\' alert alert-warning \'> не удалось добавить '+sumForUpdate+'</div>').show('fast');
                    }
                }
            }
        }
        else{
            console.log('сумма для оплаты 0');
            return false;
        }


    }
    function alertSuccess() {
        $('#sumAllPayments').before('<div id=\'forRemove\' class=\' alert alert-info \'> добавили оплату '+sumForUpdate+'</div>').show('fast');
        var alrRmv = setInterval(alertRemove, 100 );
    }
    function  alertRemove(){
        $('#forRemove').remove();
        clearInterval('alrRmv');
    }


//    var alrRmv = setInterval(alertRemove, 1000 );
//    }
//    function  alertRemove(){
//        $('#forRemove').remove();
//        clearInterval('alrRmv');
//    }
  //*функции показа успеха  не успеха в запросах
   function fUspeh() {
       (function(){
           $('.uspeh').show('slow');
           var uspehShow = setInterval(fUspehHide,2000);
       })();
        function fUspehHide() {
             $('.uspeh').hide('2000');
            clearInterval('uspehShow');
        }
       //удалим атрибут кликнутой ячейки для изменений
//       $('#forClear').removeAttr('id');
       $('#forClear').removeClass('forClear').removeAttr('id');

       // в объекте ORDER обнулим данные для изменений
       ORDER[ORDER.nameFieldForUpdate]= ORDER.newValue;
       ORDER.nameFieldForUpdate ='';
       ORDER.oldValue = '';
       ORDER.newValue = '';
   }
   function fNoUspeh() {
       (function(){
           $('.noUspeh').show('fast');
           var noUspehShow = setInterval(fNoUspehHide,1000);
       })();
       function fNoUspehHide() {
           $('.noUspeh').hide('2500');
           clearInterval('noUspehShow');
       }
       //проставим старое значение в кликнутой ячеЙке
//       $('#forClear').text(ORDER.oldValue); //не забыть удалить
       //удалим атрибут кликнутой ячейки для изменений
       $('#forClear').removeClass('forClear').removeAttr('id');

       // в объекте ORDER обнулим данные для изменений
//       ORDER[ORDER.nameFieldForUpdate]='';
       ORDER.nameFieldForUpdate ='';
       ORDER.oldValue = '';
       ORDER.newValue = '';
   }
    //*/функции показа успеха  не успеха в запросах

</script>
<script>
    //функция вызывается раз в секунду проверяет textarea если там объект {rez:true,{name:nameOrder,value:лайтбокс для фирам Рога и Копыта Чернигов} }
    // со значением true
    // значит сервер прислал результат и его надо отобразить и поменять значение в объекте ORDER
var ORDER_NEW ;
    function parseOrder(){
        var textAr = $('#newTargetForControlInput');
        ORDER_NEW = textAr.val();
        if(ORDER_NEW !=''){
//        ORDER_NEW = '{ "rez":"true","name":"$nameField","value":"$valueField" }';
            ORDER_NEW = JSON.parse( ORDER_NEW);
//            console.log(ORDER_NEW);
            if(ORDER_NEW.rez =='true'){
                console.log(ORDER_NEW.rez)
                textAr.val('');
            }
            else {
             console.log('пока еще не придумал что здесь выводить');
            }
        }

    }
//    setInterval(parseOrder,500);
</script>
    </tbody>
</table>
                            
                        </div>
                        <div class="co-lg-5">
                           
                        </div>
                    </div>
                </div>
            </div>

        </div>
    <!-- конец рабочей зоны -->
    </div>
    
    <div class="row"><!--  -->
        <div class="col-lg-12">
            
        </div>
    </div>

</div>
</body>
</html>
