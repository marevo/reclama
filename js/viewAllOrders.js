/**
 * Created by marevo on 18.08.2017.
 */
//вешаем клик на таблицу он должен вернуть id записи из кликнутой таблицы
$('#table_Orders').on('click', function () {
    // здесь   $(this) - это sortStable
    var target = event.target;//где был клик
    //выйдем на ячеку кде был клик
    if(target.closest('BUTTON') || target.nodeName =='BUTTON'){
        while (target.nodeName != 'TD'){
            target = target.parentNode;
        }
        if($(target).find('button').attr('data-id')){
            // пометим ячеку td где был клик классом для изменения .forDelClass
            $(target).addClass('forDelClass');
            console.log('click in button with atr data-id = '+ $(target).find('button').data('id'));
            var idOrder = $(target).find('button').data('id');
            var nameOrder = $(target).siblings()[1].textContent;
            console.log('название заказа '+nameOrder);
            var nameClient = $(target).siblings()[2].textContent;
            console.log('клиент '+ nameClient);
            var Order = {
                idOrder:idOrder,
                nameOrder:nameOrder,
                nameClient:nameClient
            };
            $('#viewAllOrdersModal .nameOrder').text(Order.nameOrder);
            $('#viewAllOrdersModal .idOrder').text(Order.idOrder);

            $('#viewAllOrdersModal').modal('show');
        }
        return false;
    }
    
});
//повесим поиск заказов на div .viewTrashedOrders
$('.viewTrashedOrders').on('click',function () {
    console.log('нажали div в строке поиска для показа заказов в корзине  ');
//запустим на сервер запрос для отображения удаленных заказво
    jquery_send('#rezShow','post','controllerViewAllOrders.php',
        ['showTrashedOrders'],['']);

    // var divXY = $('.viewTrashedOrders').offset();
    // $('.viewTrashedOrders').offset({top:divXY.top ,left:divXY.left+10});
    // $('.viewTrashedOrders').offset({top:50,left:100});
    // console.log($('.viewTrashedOrders').offset());

    return false;
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

$('#viewAllOrdersModal').on('shown.bs.modal',function (){
   console.log('всплыло модально окно');
    console.log('nameOrder '+$('#viewAllOrdersModal .nameOrder').text());
    console.log('idOrder =  '+$('#viewAllOrdersModal .idOrder').text());
    $('#viewAllOrdersModal .btn-danger').on('click',function () {
       var targetButton = event.target;
        var eventReletedTarget = event.relatedTarget;
        console.log('eventReletedTarget =>'+eventReletedTarget);
        if($(targetButton).hasClass('btn-danger')){
            console.log('хотим отправить запрос на удаление заказа в корзину');
            jquery_send('#rezShow','post','controllerViewAllOrders.php',
            ['sendOrderToTrash','idOrder'],
            ['', $('#viewAllOrdersModal .idOrder').text()]);
            $('#viewAllOrdersModal').modal('hide');
            return false;
        }
    });
});

$('#viewAllOrdersModal').on('hidden.bs.modal',function () {
    $('#viewAllOrdersModal .nameOrder').text('');
    $('#viewAllOrdersModal .idOrder').text('');
    console.log('почистили idOrder and nameOrder в модальном окне');
    console.log('закрылось модально окно');
    
});

//повесим на строку с классом rowSearch обработчик поиска по названию заказа или по имени клиента
$('.rowSearch').ready().on('click',function () {
    var target = event.target;
    console.log('target = '+target +'target.name = '+target.name);
    //поиск заказа по подобности  названию клиента
    if(target.name == 'searchForNameClient'){
        var nameClient = $.trim($('input[name="inputFindOrderForNameClient"]').val());
        if(nameClient != '' && nameClient.length >2){
            jquery_send('.divForAnswerServer','post','controllerViewAllOrders.php',
                ['searchOrderForNameClient','nameClient'],
                ['', nameClient]
            );
            console.log('ищем по названию клиента searchForName= '+ nameClient+" ");
        }
        else{
            $('input[name="inputFindOrderForNameClient"]').attr('placeholder',' минимум 3 символа').val('');
        }

        return false;
    }
    //поиска заказа по подобию названию заказа
    if(target.name == 'searchForName'){
        console.log('ищем по названию заказа searchForName');
        var nameOrder = $.trim($('input[name="inputFindOrderForName"]').val());
        if(nameOrder != '' && nameOrder.length >2){
            jquery_send('.divForAnswerServer','post','controllerViewAllOrders.php',
                ['searchOrderForName','nameOrder'],
                ['', nameOrder]
            );
            console.log('ищем по названию клиента searchForName= '+ nameOrder +" ");
        }
        else {
            $('input[name="inputFindOrderForName"]').attr('placeholder',' минимум 3 символа').val('');
        }
        return false;
    }

});

// function findOrderFor(nameButton){
//     if(nameButton =='searchForName'){
//         console.log('поиск по названию заказа');
//
//         return false;
//     }
//     if(nameButton == 'searchForNameClient'){
//         console.log('поиск по клиенту');
//
//         return false;
//     }
// }