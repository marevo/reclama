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
<html lang="ru-RU">
<title> просмотр данных заказа </title>
<?php include('../head.html') ?>
<body>
<div class="container">
    <div class="row">
        <?php require_once('header.html'); ?>
    </div>
    <div class="row"><!-- навигация -->
        <?php include('../navigation.html');?>
        <script>
showLi('');
        </script>
    </div>
    <div class="row">
        <!--рабочее место слева для будущего меню-->
        <div class="col-lg-2 backForDiv">
            этот див слева от таблицы в нем можно расположить дополнительные кнопки добавить редактировать удалить
        </div>
        <!--/рабочее место слева для будущего меню-->
            <?php echo //передаем объект ORDER для распределения
            "<script>
 var ORDER = {
     id : '$order->id',
     descriptionOrder: '$order->descriptionOrder',
     nameOrder : '$order->name',
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
 </script>
             ";
            ?>
        <!--рабочее место справа-->
        <div class="col-lg-10 backForDiv ">
            <!--строка показа времени и показа результата добавки материала в базу  -->
            <?php  include_once '../App/html/forDisplayTimeShowAnswerServer.html'?>
            <!--  блок отображения что меняем и кнокпки обновить страницу и кнопка править(покажет поля для внесения новых значений)  -->
            <div class="row headingContent">
                <div class="col-lg-9   col-md-9 col-sm-9 col-xs-9   text-center ">просмотр/правка заказа <?php echo $order->name; ?> для <?php echo $nameClient; ?></div>
<!--                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center"><button class="btn btn-sm btn-default" id="btnUpdateShow" >обновить</button></div>-->
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center"">
                <button class="btn btn-sm btn-primary btnAddMatetialToOrder" title="добавить материал к заказу">
                    <span class="glyphicon glyphicon-plus-sign"></span> материал
                </button>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-12 divForTable" >
<table id="tableOneOrder" data-idorder="">
    <thead><tr><td>данные заказа</td><td>значение</td><td></td></tr></thead>
    <tbody>
    <tr><td>название заказа</td><td data-name="nameOrder"></td><td><span class="glyphicon glyphicon-edit"> править</span></td></tr>
    <tr><td>описание заказа</td><td data-name="descriptionOrder"></td><td><span class="glyphicon glyphicon-edit"> править</span></td></tr>
    <tr><td>источник</td><td data-name="source"></td><td><span class="glyphicon glyphicon-edit"> править</span></td></tr>
    <tr title="название клиента для которого делаем заказ"><td>название клиента</td><td data-name="nameClient"></td><td><span class="glyphicon glyphicon-edit"> править</span></td></tr>
    <tr><td>цена</td><td data-name="orderPrice"></td><td><span class="glyphicon glyphicon-edit"> править</span></td></tr>
    <tr><td>цена комплектующих</td><td data-name="manufacturingPrice"></td><td><span class="glyphicon glyphicon-eye-open"> просмотр компл</span></td></tr>
    <tr><td>комплектация: укомплектован-да   не укомплектован-нет</td><td data-name="isCompleted" ></td><td><span class="glyphicon glyphicon-edit"> править</span></td></tr>
    <tr><td> статус </td><td data-name='isReady'></td><td><span class="glyphicon glyphicon-edit"> править</span></td></tr>
    <tr><td>установлен</td><td data-name='isInstall'></td><td><span class="glyphicon glyphicon-edit"> править</span></td></tr>
    <tr><td title="сколько уже проплатил клиент">оплата</td><td data-name="sumAllPayments" id="sumAllPayments" ></td>
        <td id="addPayment" data-name ='addPayment'><span class="glyphicon glyphicon-plus"> внести оплату</span></td>
    </tr>
     <tr><td title='нач: дата начала заказа'>дата нач </td><td data-name="dateOfOrdering"></td><td><span class="glyphicon glyphicon-edit"> править</span></td></tr>
    <tr><td title='дата завершения заказа'>дата кон </td><td data-name="dateOfComplation"></td><td><span class="glyphicon glyphicon-edit"> править</span></td></tr>
    <tr><td title='разрешение на изменение всех цен в этом заказе при добавлении или изменении материалов'> разрешение на  автоматический пересчет цены комплекующих</td>
        <td data-name="isAllowCalculateCost"></td><td><span class="glyphicon glyphicon-edit"> править</span></td></tr>
    <tr><td title='удаление заказа поставим метку что заказ в корзине'>отправить в корзину заказ</td><td data-name="isTrash"></td><td><span class="glyphicon glyphicon-edit"> править</span></td></tr>
    </tbody>
</table>
    <script>
    //*на событие загрузки таблицы добавили на саму таблицу dbclick и click функцию al
    var svetofor1 = 'green',//светофор для ввода оплат
        svetofor2 = 'green';//всетофор для правки других полей нельзя вызвать следующее поле пока не закрыли, то что правиться
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
                svetofor1 = 'green';
                return false;
            }
            if(target.nodeName == 'TR' )
                return ;
            //обработчик клика на кнопках "отправить" "передумал" в среднем столбце таблицы
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
//обработка клика по добавлению оплаты
            if($(target).data('name') == 'addPayment'){
                var tdWhereWasClick = target ;//td где был клик
                console.log ('зашли в обработку клика добавления оплаты надо сразу выйти из обработки клика');
                //вызовем функцию по обработке клика на добавление платежа
                if(svetofor1 &&  svetofor1 == 'green'){
                    fAddPayment(tdWhereWasClick,event,svetofor1);//не забыть удалить
                }
            }
            //если кликнули в левом столбце таблицы то надо игнорировать и выйти из функции обработки клика
            var numberCellInTr = $(target).index();
            if(numberCellInTr < 2  )//не обрабатывать и выход если кликнуть в левой или средней колонке
                return ;

            console.log('click : numberCellInTr кликнули в '+ numberCellInTr + 'столбце' );
            var trWhereWasClick;
            while(target.nodeName != 'TR' ){
                target = target.parentNode;
            }
            trWhereWasClick = target;
            //если siblings слева есть атрибут типа data-name
            var tdSiblingLeft = $(trWhereWasClick).children()[1];
//          var tdSiblingLeft = trWhereWasClick.childNodes[1];
            //эмулируем dblclick() по td слева
            $(tdSiblingLeft).dblclick();
            if($(tdSiblingLeft).data('name') == 'manufacturingPrice'){
                //  ***              вызов функции модального окна
                $('#modalViewAllMaterialsToThisOrder').modal('show');
            }
        })
    });
    $('#tableOneOrder').ready(function() {
        $('#tableOneOrder tbody ').on('dblclick' ,function(event) {
            if (svetofor2 == 'red')
                return;
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
            //вставка в DOM поля для изменения с кнопками #вставка в DOM в зависимости от типа названия поля для update
            if(ORDER.nameFieldForUpdate !=''){
                //изменение клиента для которого делается заказ
                if(fildName =='nameClient'){
                    //выбросим в td где был dblclick select id= 'forClearNameClient'
                    $(tdWhereWasClick).html(inputForm(ORDER.nameFieldForUpdate));
                    //в выброшеный select вставим option с назаниями клиентов
                    //полшлем запрос через ajax для заполнения выбора клиентов через select и загрузим их в select id='forClearNameClient'
                        jquery_send('#forClearNameClient','get','../controllerOneOrder.php',
                            ['selectAllClients','idOrder', 'idClient'],
                            ['', ORDER.id , ORDER.idClient ]
                        );
                }
                //изменение названия заказа или описания заказа
                if(fildName == 'nameOrder' || fildName == 'descriptionOrder'){
                    $(tdWhereWasClick).html(inputForm(ORDER.nameFieldForUpdate));
                }
                // изменение источника с которого пришел заказ
                // если dblclick кликнули в поле source || isCompleted || isReady || isInstall || isTrash || isAllowCalculateCost begin
                if(fildName == 'source' ||
                   fildName == 'isCompleted' ||
                   fildName == 'isReady' ||
                   fildName == 'isInstall' ||
                   fildName == 'isTrash' ||
                   fildName == 'isAllowCalculateCost'
                ){
                    $(tdWhereWasClick).html(inputForm(ORDER.nameFieldForUpdate));
                    //считать значение из input type='ratio'
//                        прошли по всем radio и установили на нужном  checked
                    $('#forClear input[name='+ ORDER.nameFieldForUpdate + ']').each(function () {
                            if( $(this).val() ==  ORDER.oldValue ){
                                $(this).prop('checked',true);
                            }
                            else
                                $(this).prop('checked',false);
                    });
                }//*/nameFiedForUpdaet ='source' end
                //*изменить цену для клиента
                if(fildName == 'orderPrice'){
                    $(tdWhereWasClick).html(inputForm(ORDER.nameFieldForUpdate));
                }
                if(fildName == 'dateOfOrdering' || fildName == 'dateOfComplation'){
                    $(tdWhereWasClick).html(inputForm(ORDER.nameFieldForUpdate));
                }
            }
            else{
                //отказ от изменений данных заказа
                refusalUpdate();
                allocateOrderField();
            }
//         forUpdate(tdWhereWasClick);// не забыть удалить эта функция лишняя надо бы удалить

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
            allocateOrderField();
        });
    });
//*функция "распределения по местам" на странице данных объекта ORDER
    /**
     *функция "распределения по местам" на странице данных объекта ORDER
     */
    function allocateOrderField() {
        var i=0;
        for  (var k in ORDER){
            if(ORDER[k]!= 'undefined' && ORDER[k]!='' ){
                var tdToAllocate = $('[data-name = '+ k +']');
                if( k== 'id' || k == 'manufacturingPrice' || k == 'nameClient'
                    || k== 'nameOrder' || k=='orderPrice'|| k=='sumAllPayments'
                    || k=='dateOfComplation' || k=='dateOfOrdering'
                    || k=='descriptionOrder'
                ){
//                    var III = 0;
                    $(tdToAllocate).each(function () {
//                        if(k == 'manufacturingPrice')
//                            $(tdToAllocate).css('backgroundColor','red');
                        $(this).text(ORDER[k]);
//                        III++;
//                        console.log('k=='+k + '  III=='+ III);
                    });
                }
                //                console.log(k+':'+ORDER[k]);

                if(k =='isReady'){
                    switch (ORDER[k]){
                        case '0' :
                            tdToAllocate.removeClass('orderSuccessCell').addClass('orderNoSuccessCell').text('новый');
                            break;
                        case '3' :
                            tdToAllocate.removeClass('orderSuccessCell').addClass('orderNoSuccessCell').text('в работе');
                            break;
                        case '1' :
                            tdToAllocate.removeClass('orderNoSuccessCell').addClass('orderSuccessCell').text('закрыт успешно');
                            break;
                        case '2' :
                            tdToAllocate.removeClass('orderNoSuccessCell').addClass('orderSuccessCell').text('закрыт не успешно');
                            break;
                    }
                }
                if(k == 'isInstall'){
                    switch (ORDER[k]){
                        case '0':
                            tdToAllocate.removeClass('orderSuccessCell').addClass('orderNoSuccessCell').text('не установлен');
                            break;
                        case '1':
                            tdToAllocate.removeClass('orderSuccessCell').addClass('orderNoSuccessCell').text('в процессе установки');
                            break;
                        case '2':
                            tdToAllocate.removeClass('orderNoSuccessCell').addClass('orderSuccessCell').text('установлен успешно');
                            break;
                    }
                }
                if(k == 'isCompleted'){
                    switch (ORDER[k]){
                        case '0':
                            tdToAllocate.removeClass('orderSuccessCell').addClass('orderNoSuccessCell').text('нет').attr('data-value',ORDER[k]);
                            break;
                        case '1':
                            tdToAllocate.removeClass('orderNoSuccessCell').addClass('orderSuccessCell').text('да').attr('data-value',ORDER[k]);
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
        $('.dateToday').html(date)  ;
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
        svetofor2 = 'red';
        var htmString;//будущее поле с кнопками отправить и передумал

        var inputt;
        var submitt ="<br/><input   class='button  btn-group-vertical btn-primary' type='button' name='submitInputForm'   value = 'изменить' />";
        var nosubmitt ="<input class='button btn-group-vertical btn-info' type='button' name='noSubmitInputForm'  value = 'передумал' />";
//        var scriptInputt="";
        if(nameField == 'nameOrder' || nameField == 'descriptionOrder'  ) {
            inputt = "<textarea id='idInputForm' name='" + nameField + "' value='" + ORDER.oldValue +
                "' autofocus cols='30' rows='4'>"+ORDER.oldValue+"</textarea>";
        }
        if(nameField == 'source'){
            //#работаю здесь надо выставить источник таким же как он есть в отображении
//            $('#forClear').('')
            console.log('значение ORDER[data-name] = '+ORDER[nameField]);
            ORDER.nameFieldForUpdate = nameField;
            ORDER.oldValue = ORDER[nameField];

            inputt ="<input type='radio' name='source' value='0'/>не известен<br>"+
                "<input type='radio' name='source' value='1'/>входящий звонок<br>"+
                "<input type='radio' name='source' value='2'/>prom.ua<br>"+
                "<input type='radio' name='source' value='3'/>olx<br>"+
                "<input type='radio' name='source' value='4'/>сайте<br>"+
                "<input type='radio' name='source' value='5'/>объявление в газете<br>"+
                "<input type='radio' name='source' value='6'/>другое";
        }
        //*заказ укомплектован материалами 0-нет 1-да
        if(nameField == 'isCompleted'){
            console.log('значение ORDER[data-name] = '+ORDER[nameField]);
            ORDER.nameFieldForUpdate = nameField;
            ORDER.oldValue = ORDER[nameField];
            inputt = "<input type='radio' name = '"+ nameField+"' value='0'/> не укомплектован<br/>"+
                     "<input type='radio' name = '"+ nameField+"' value='1'/> укомплектован";
        }
        //*/заказ укомплектован материалами 0-нет 1-да

        //*степень готовности заказа 0-надо еще посчитать и связаться с заказчиком для утверждения цены
        // 1-закрыт успешно
        // 2-закрыт не успешно
        // 3-запущен в работу
        if(nameField == 'isReady'){
            console.log('значение ORDER[data-name] = '+ORDER[nameField]);
            ORDER.nameFieldForUpdate = nameField;
            ORDER.oldValue = ORDER[nameField];
            inputt = "<input type='radio' title='надо считать и утверждать у клиента' name = '"+ nameField+"' value='0'/> новый <br/>"+
                "<input type='radio' name = '"+ nameField+"' value='1'/> закрыт успешно<br/>"+
                "<input type='radio' name = '"+ nameField+"' value='2'/> закрыт  не успешно<br/>"+
                "<input type='radio' name = '"+ nameField+"' value='3'/> запущен в работу<br/>";
        }
        //*/степень готовности заказа конец end
        //*установлен у клиента
        //0-не установлен
        //1- в процессе
        //2- установлен
        if(nameField == 'isInstall'){
            console.log('значение ORDER[data-name] = '+ORDER[nameField]);
            ORDER.nameFieldForUpdate = nameField;
            ORDER.oldValue = ORDER[nameField];
            inputt = "<input type='radio' name = '"+ nameField+"' value='0'/> не установлен <br/>"+
                "<input type='radio' name = '"+ nameField+"' value='1'/> в процессе установки<br/>"+
                "<input type='radio' name = '"+ nameField+"' value='2'/> установлен у клиента";
        }
        //*/установлен у клиента
        if(nameField == 'isTrash'){
            console.log('значение ORDER[data-name] = '+ORDER[nameField]);
            ORDER.nameFieldForUpdate = nameField;
            ORDER.oldValue = ORDER[nameField];
            inputt = "<input type='radio' title='не удален' name = '"+ nameField+"' value='0'/> не удален <br/>"+
                "<input type='radio' title='удален еще не потерян (перемещен в корзину) можно восстановить все данные заказа с правами администратора' name = '"+ nameField+"' value='1'/> удален <br/>";
        }
        //**разрешить добавку материалов к заказу и изменение цены для всех материалов если изменилась цена в таблице материалы
        if(nameField == 'isAllowCalculateCost'){
            console.log('значение ORDER[data-name] = '+ORDER[nameField]);
            ORDER.nameFieldForUpdate = nameField;
            ORDER.oldValue = ORDER[nameField];
            inputt = "<input type='radio' title='запрет' name = '"+ nameField+"' value='0'/> запрет <br/>"+
                "<input type='radio' title='разрешено добавлять материалы и автоматически изменять их цены ' name = '"+ nameField+"' value='1'/> разрешено <br/>";

        }
        //*/разрешить добавку материалов к заказу и изменение цены для всех материалов если изменилась цена в таблице материалы
        //меняем принадлежность к клиенту
        if(nameField == 'nameClient'){
            inputt = "<select id='forClearNameClient'>выберите клиента из списка></select>";
        }
        //меняем цену
        if(nameField == 'orderPrice'){
           inputt = '<input id="inputOrderPrice"  type="text" value="'+ ORDER.orderPrice +'" autofocus/>';
        }
        if(nameField == 'dateOfOrdering'){
            inputt = '<input type="date" name="dateOfOrdering" value="'+getDate() +'" />';
        }
        if(nameField == 'dateOfComplation'){
            inputt = '<input type="date" name="dateOfComplation" value="'+getDate() +'" />';
        }
        htmString = inputt + submitt + nosubmitt ;
        return htmString;

    }
    //*/функция вставки в изменяемое поле форму с кнопками
    //*обработка валидации и запрос в базу для update ORDER
    //*функция обработки  click по кнопкам изменить и передумал
    function zaprosInput() {
        var target = event.target;
        //передумали вносить изменения в поле источник заказа
        if(target.name =='noSubmitInputForm'){
            //запустим функцию отображения на странице
            allocateOrderField();
            //уберем выделение через класс forClear
            refusalUpdate();
            svetofor2 = 'green';
            return false;
        }

        //*обработка валидации и запрос в базу для update ORDER в поле источник заказа ORDER.source || ORDER.isCompleted || ORDER.isReady ||isInstall
        if($(target).siblings()[0].type == 'radio' ){
            console.log('будем обрабатывать как  radio');
            //считаем значение выбранного radio для поля
            ORDER.newValue =  $('#forClear input[type="radio"]:checked').val();
            console.log('выбрано val() : '+ ORDER.newValue);
            if(target.name =='submitInputForm' && ORDER.newValue !='' && ORDER.newValue != ORDER.oldValue){
                console.log('сейчас старый' + ORDER.nameFieldForUpdate +'='+ORDER.oldValue+'  на сервер отправим:'  + ORDER.newValue);
                jquery_send('#rezShow','get','../controllerOneOrder.php',
                    ['update','nameField','valueField','idOrder'],
                    ['',ORDER.nameFieldForUpdate,ORDER.newValue,ORDER.id]);
                //очистим поля для update
                clearFildForUpdate();
            }
        }
        //*/обработка изменений  в поле источник заказа ORDER.source

        if($(target).siblings()[0].type == 'textarea'){
            console.log('будем обрабатывать как textarea');
            ORDER.newValue = $.trim($('#idInputForm').val());
            if(target.name =='submitInputForm' && ORDER.newValue != ORDER.oldValue){
                console.log('на сервер отправим '+ORDER.nameFieldForUpdate + " : "+ORDER.newValue);
                jquery_send('#forClear','get','../controllerOneOrder.php',
                    ['update','nameField','valueField','idOrder'],
                    ['',ORDER.nameFieldForUpdate,ORDER.newValue,ORDER.id]);
                //очистим поля для update
                clearFildForUpdate();
            }
        }

        if($(target).siblings()[0].type == 'select-one'){
            console.log('будем обрабатывать select');
            if(target.name == 'submitInputForm'){
                //считаем значение выбранного select - option
                ORDER.oldValue = ORDER.idClient;
                ORDER.newValue = $('#forClearNameClient option:checked').val();
                ORDER.nameFieldForUpdate = 'idClient';
                console.log('ORDER.nameFieldForUpdate = '+ ORDER.nameFieldForUpdate +
                    '  ORDER.oldValue = '+ ORDER.oldValue +
                    '  ORDER.newValue = '+ ORDER.newValue);
                if(ORDER.newValue != ORDER.oldValue){//выбрали выбрали нового клиента
                    //пошлем запрос на изменение клиента (привязку другого клинета к заказу)
                    jquery_send('#rezShow','get','../controllerOneOrder.php',
                        ['update','nameField','valueField','idOrder'],
                        ['',ORDER.nameFieldForUpdate,ORDER.newValue,ORDER.id]
                    );
                    //очистим поля для update
                    clearFildForUpdate();
                }
            }
        }
        //изменение суммы заказа
        if( $(target).siblings()[0].type == 'text' && $(target).siblings()[0].id == 'inputOrderPrice'){
            ORDER.oldValue = ORDER.orderPrice;
            var sumForTest = $('#inputOrderPrice').val();
            if(testSumOnFloat(sumForTest) && +sumForTest > 0 ){
                ORDER.newValue = sumForTest;
                $('#inputOrderPrice').removeClass('orderNoSuccessCell').addClass('orderSuccessCell');
            }
            else{
                $('#inputOrderPrice').removeClass('orderSuccessCell').addClass('orderNoSuccessCell');
                ORDER.newValue = '';
            }
            ORDER.nameFieldForUpdate = 'orderPrice';
            if(ORDER.newValue != ORDER.oldValue && testSumOnFloat(sumForTest) && +sumForTest > 0){
                console.log('отправляем на update ORDER.nameFieldForUpdate = '+ ORDER.nameFieldForUpdate +
                    '  ORDER.oldValue = '+ ORDER.oldValue +
                    '  ORDER.newValue = '+ ORDER.newValue);
                jquery_send('#rezShow','get','../controllerOneOrder.php',
                    ['update','nameField','valueField','idOrder'],
                    ['',ORDER.nameFieldForUpdate,ORDER.newValue,ORDER.id]
                );
                //очистим поля для update
                clearFildForUpdate();
            }
        }  //*/изменение суммы заказа
        //изменение даты взятия заказа
        if($(target).siblings()[0].type == 'date' ){
//        if($(target).siblings()[0].type == 'date' && $(target).siblings()[0].id ==  ){
            console.log('будем обрабатывать как дату');

            ORDER.oldValue = ORDER.dateOfOrdering;
            ORDER.newValue =  $('#forClear input[type="date"]').val();
            console.log('выбрано val() : '+ ORDER.newValue);
            if(target.name =='submitInputForm' && ORDER.newValue !='' && ORDER.newValue != ORDER.oldValue){
                console.log('сейчас старый' + ORDER.nameFieldForUpdate +'='+ORDER.oldValue+'  на сервер отправим:'  + ORDER.newValue);
                jquery_send('#rezShow','get','../controllerOneOrder.php',
                    ['update','nameField','valueField','idOrder'],
                    ['',ORDER.nameFieldForUpdate,ORDER.newValue,ORDER.id]);
                //очистим поля для update
                clearFildForUpdate();
            }
        }
//разрешим заново производить update
        svetofor2 = 'green';
    } //*/функция обработки запроса к кнопкам изменить и передумал
    //*/функция которая будет вызываться для отправки запроса на update
    //*функция добавления оплаты
    function fAddPayment(tdWhereWasClick, event) {
        svetofor1 = 'red';
        //где кликнули var tdWhereWasClick
//        если есть уже input то не будем добавлять еще, а выйдем
            console.log('зашли в ф fAddPayment она добовит поле input вместо <<+ оплатить>>');
            //определим в input для вставки вместо старой суммы
            var inputToAddPayments = "" +
                "<input id='inSum' name='addSum' type='text' value='0.00'  autofocus />"+
                "<input name='inSumNo' class='button btn-success' type = 'button' value='передумал' />";
            $(tdWhereWasClick).html(inputToAddPayments);
//        сразу вешаем событие на клик в нашем новом input
//            $('#inSum').on('input',function (event) {//почему input не работает, а keyup работает
            $('#inSum').on('keyup',function (event) {
                var target = event.target;
                if(testSumOnFloat( $('#inSum').val())){
                    $('#inSum').removeClass('orderNoSuccessRow').addClass('orderSuccessRow');
                }
                else{
                    $('#inSum').removeClass('orderSuccessRow').addClass('orderNoSuccessRow');
                }
                if(target.nodeName == 'INPUT'){
                    var sum = $('#inSum').val();
                    if( event.keyCode == 13 && sum !=0 && sum !='' ){
                        //провести валидацию введенной суммы оплаты
                        if(testSumOnFloat(sum)){
                            //отправим сумму оплаты
                            jquery_send('#rezShow','get','../controllerOneOrder.php',
                                ['addSum','idOrder','idClient','sum','dateToday'],
                                ['', ORDER.id,ORDER.idClient, sum, getDate()]);
                            $('#addPayment').html('<span class="glyphicon glyphicon-plus"></span>'); //не забыть
                            //включим зеленый свет светофора(может быть вызвано опять поле добавки оплаты)
                            svetofor1 = 'green';
                        }
                    }
                    else {
                        console.log('не отправили на сервер event.keyCode == '+ event.keyCode +' sum '+sum );
                    }
                    return false;//не забыть удалить
                }
            });
    }
//*функции показа успеха добавления или не добавления оплаты //не использую не забыть удалить
    function alertSuccess() {
        $('#sumAllPayments').before('<div id=\'forRemove\' class=\' alert alert-info \'> добавили оплату '+sumForUpdate+'</div>').show('fast');
        var alrRmv = setTimeout(alertRemove, 100 );
    }
    function  alertRemove(){
        $('#forRemove').remove();
        clearTimeout('alrRmv');
    }
  //*функции показа успеха  не успеха в запросах
   function fUspeh() {
       //$('.divForShowAnswerServer')//по идее здесь будут отображаться результаты запроса к серверу
       $('.uspeh').show('slow');
       var uspehShow = setTimeout(fUspehHide,2000);
       allocateOrderField();//не забыть удалить - это запуск распределения по местам значений объекта ORDER при ответе сервера
       function fUspehHide() {
          $('.uspeh').hide('2000');
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
       $('.noUspeh').show('1500');
       var alrRmv = setTimeout(fNoUspehHide,1000);
       allocateOrderField();//не забыть удалить - это запуск распределения по местам значений объекта ORDER при ответе сервера
       function fNoUspehHide() {
           $('.noUspeh').hide('2500');
           clearTimeout('alrRmv');
       }
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
    // значит сервер прислал и его надо отобразить и поменять значение в объекте ORDER
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
                </div>
            </div>
        </div>
    <!-- конец рабочей зоны -->
    </div>
    <?php
    //подключение модального окна для просмотра материалов к заказу
    include_once('viewAllMaterialsToOrder.html');
    //подключение модального окна для добавления материалов к заказу
    include_once('formAddMaterialToOrderModal.html');
    ?>

</div><!-- container-->
<script src="../js/viewOneOrder.js"></script>
</body>
</html>
