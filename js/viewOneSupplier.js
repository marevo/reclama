/**
 * Created by marevo on 12.08.2017.
 */
//внимание не забыть удалить этот js viewOndeSupplier.js
//на загрузку страницы добавим запуск функции по распределению полей объекта ORDER по местам страницы
$('#tableOneSupplier').ready(function(){
    //console.log('таблица загружена запустим расстановку значений ORDER согласно атрибута data-name');
    $('SUPPLIER').ready(function (){
        console.log(' загружена таблица tableOneSupplier и загружен объкте SUPPLIER');
        //запустим функцию расстановки по местам полей объкта ORDER
        allocateSupplierField();
    });
});
//*функция обработки click  которая будет вызываться для отправки запроса на update
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
//*функция "распределения по местам" на странице данных объекта ORDER
/**
 *функция "распределения по местам" на странице данных объекта SUPPLIER
 */
function allocateSupplierField() {
    var i=0;
    for  (var k in SUPPLIER){
        if(SUPPLIER[k]!= 'undefined' && SUPPLIER[k]!='' ){
            var tdToAllocate = $('[data-name = '+ k +']');
            if( k== 'id' || k == 'name' || k == 'contactPerson'
                || k== 'addCharacteristic' || k=='phone0'|| k=='email0'
                || k=='address'
            ){
                tdToAllocate.text ( SUPPLIER[k] ) ;
            }
            //                console.log(k+':'+ORDER[k]);

            if(k =='deliveryDay'){
                switch (SUPPLIER[k]){
                    case '6' :
                        tdToAllocate.removeClass('orderSuccessCell').addClass('orderNoSuccessCell').text('суббота');
                        break;
                    case '0' :
                        tdToAllocate.removeClass('orderSuccessCell').addClass('orderNoSuccessCell').text('воскресенье');
                        break;
                    case '1' :
                        tdToAllocate.removeClass('orderNoSuccessCell').addClass('orderSuccessCell').text('понедельник');
                        break;
                    case '2' :
                        tdToAllocate.removeClass('orderNoSuccessCell').addClass('orderSuccessCell').text('вторник');
                        break;
                    case '3' :
                        tdToAllocate.removeClass('orderNoSuccessCell').addClass('orderSuccessCell').text('среда');
                        break;
                    case '4' :
                        tdToAllocate.removeClass('orderNoSuccessCell').addClass('orderSuccessCell').text('четверг');
                        break;
                    case '5' :
                        tdToAllocate.removeClass('orderNoSuccessCell').addClass('orderSuccessCell').text('пятница');
                        break;
                }
            }
            if(k == 'site'){
                $(tdToAllocate).html('<a href="'+SUPPLIER[k] +'" target="_blank">'+ SUPPLIER[k] +' </a>');
            }
        }
        else
            console.log('i='+ ++i +' -> ' +k+':'+ SUPPLIER[k]);
    }//конец for in
}//конец функции

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
        allocateSupplierField();
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

//*функции показа успеха добавления или не добавления оплаты //не использую не забыть удалить
function alertSuccess() {
    $('#sumAllPayments').before('<div id=\'forRemove\' class=\' alert alert-info \'> добавили оплату '+sumForUpdate+'</div>').show('fast');
    setTimeout(alertRemove, 100 );
}
function  alertRemove(){
    $('#forRemove').remove();
}
//*функции показа успеха  не успеха в запросах
function fUspeh() {
    (function(){
        $('.uspeh').show('slow');
        setTimeout(fUspehHide,0);
        allocateOrderField();//не забыть удалить - это запуск распределения по местам значений объекта ORDER при ответе сервера
    })();
    function fUspehHide() {
        $('.uspeh').hide('2000');
    }
    //удалим атрибут кликнутой ячейки для изменений
//       $('#forClear').removeAttr('id');
    $('#forClear').removeClass('forClear').removeAttr('id');

    // в объекте ORDER обнулим данные для изменений
    SUPPLIER[SUPPLIER.nameFieldForUpdate]= SUPPLIER.newValue;
    SUPPLIER.nameFieldForUpdate ='';
    SUPPLIER.oldValue = '';
    SUPPLIER.newValue = '';
}
function fNoUspeh() {
    (function(){
        $('.noUspeh').show('1500');
        var noUspehShow = setTimeout(fNoUspehHide,1000);
        allocateOrderField();//не забыть удалить - это запуск распределения по местам значений объекта ORDER при ответе сервера
    })();
    function fNoUspehHide() {
        $('.noUspeh').hide('2500');
    }
    $('#forClear').removeClass('forClear').removeAttr('id');

    // в объекте ORDER обнулим данные для изменений
//       ORDER[ORDER.nameFieldForUpdate]='';
    ORDER.nameFieldForUpdate ='';
    ORDER.oldValue = '';
    ORDER.newValue = '';
}
//*/функции показа успеха  не успеха в запросах

<!-- надо придумать что делать с обработкой просмотра и правки одного поставщика -->
$('#tableOneSupplier').ready(function() {
    $('#tableOneSupplier tbody ').on('click' ,function(event) {
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