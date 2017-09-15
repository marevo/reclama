/**
 * Created by marevo on 14.09.2017.
 */

$('#modalViewAllPaymentsToThisOrder').on('show.bs.modal',function (idOrder) {
    //отправляем запрос на загрузку данных всех оплат из базы в таблицу #tableAllPaymentsForThisOrder
    console.log('отпавим запрос на получение данных всех оплат по idOrder='+
        $('#modalViewAllPaymentsToThisOrder').find('[data-idOrder]').text());
    jquery_send('#tableAllPaymentsForThisOrder', 'post', '../app/controllers/controllerModalWinShowAllPayments.php',
        ['loadPaymentForOrder', 'idOrder'], ['', $('#modalViewAllPaymentsToThisOrder').find('[data-idOrder]').text()]);
    //выставим дату сегодняшнюю
    //var dateThisDay = getDate();
    $('#idModalWinDatePayment').val(getDate());
    //повесим проверку на валидность суммы оплаты
    povesitProverkuValidnostyNaInput('#idModalWinValPayment');

    //Добавка оплаты через модальное окно просмотра всех оплат по данному заказу
    $('#idModWinBtnAddPayment').on('click', addPaymentInModalWinAllPayments);

//    вешаем клик на отобрыжение кнопок удаления
    $('#btnModalShowButtonsTrashInTable').on('click', showButtonsTrashInTableAllPayments);

//повесим клик на удаление конкретного платежа
    $('#tableAllPaymentsForThisOrder ').on('click', deleteThisPaymentFromBase);
});


$('#modalViewAllPaymentsToThisOrder').on('hide.bs.modal',function () {
    $('#btnModalShowButtonsTrashInTable').unbind('click',showButtonsTrashInTableAllPayments);
    $('#idModWinBtnAddPayment').unbind('click', addPaymentInModalWinAllPayments);
    $('#tableAllPaymentsForThisOrder ').unbind('click', deleteThisPaymentFromBase);
});
//функция клика по кнопке показа в этом модальном окне, которая показывает значки удалить напротив каждой оплаты
function showButtonsTrashInTableAllPayments() {
    $('#tableAllPaymentsForThisOrder .tdDisplayNone').css('display',
        function(i,value){
            if(value == 'none')
                return  'block';
            else
                return 'none';
        });
    return false;
}
function addPaymentInModalWinAllPayments() {
    console.log(' пробуем добавить оплату добавляем оплату по этому заказу');
//если сумма добавки 0 или отрицательна или заказ закрыт успешно или закрыт не успешно то не сможем добавить заказ
    if($('#idModalWinValPayment').val() > 0 &&  ORDER.isReady != 2 && ORDER.isReady !=1  ){
        //  отправляем запрос на добавку в базу оплаты
        console.log('добавляем оплату по этому заказу');
        jquery_send('#modalViewAllPaymentsToThisOrder .divShowAnswerServer','post','../app/controllers/controllerModalWinShowAllPayments.php',
            ['sendPaymentForOrderFromModalWin','sumPayment','idOrder','idClient','datePayment'],
            ['',$('#idModalWinValPayment').val(),ORDER.id,ORDER.idClient, $('#idModalWinDatePayment').val()]);
        $('#idModalWinValPayment').val('0');
        console.log('улетели данные на добавку оплаты в базу idOrder')
    }
    else {
        $('#idModalWinValPayment').val('0');
        fNoUspehAll('нельзя добавить оплату, т.к. заказ закрыт или сумма добавки 0');
    }

    return false;
}
//функция удаления оплаты из базы оплат
function     deleteThisPaymentFromBase (event) {
    var target = event.target;
    while (target.tagName !='TABLE'){
        if(target.tagName == 'BUTTON'){
            if( $(target).hasClass('btnDeleteThisPayment') ){
                console.log('click in table target=button trash');
                var idPaymentForDelete = $(target).data('id');
                console.log('хотим удалить платеж с id = '+ idPaymentForDelete);
                jquery_send('#modalViewAllPaymentsToThisOrder .divShowAnswerServer','post','../app/controllers/controllerModalWinShowAllPayments.php',
                    ['sendDeletePaymentForOrderFromModalWin','idPaymentForDelete'],
                    ['',idPaymentForDelete]);
                return false;
            }
        }
        target = target.parentNode;
    }
    return false;
}
//по загрузке страницы повесим на таблицу где показаны  все оплтаты всех заказов
$(function () {
    //функция обработки клика на таблице всех оплат
    $('#tbViewAllPayments').on('click',function (event) {
        var target = event.target;
        if(target.nodeName == 'SPAN' && target.parentNode.nodeName=="BUTTON"  )
            target = target.parentNode;
        if(target.nodeName== 'BUTTON' && target.name =='btnViewModalAllPaymentThisOrder'){
            var idOrder = $(target).data('idorder');
            var dataPayment = $(target).data('payment');
            console.log('click on button for viewAllPaymentsForThisOrder with idOrder='+ idOrder);
            // занесем даный idOrder  в модальное окно что бы при его показе подгрузить все оплаты по этому id
            $('#modalViewAllPaymentsToThisOrder').
            find('[data-idOrder]').text(dataPayment.idOrder).end().
            find('[data-nameOrder]').text(dataPayment.nameOrder).end().
            find('[data-idClient]').text(dataPayment.idClient).end().
            find('[data-nameClient]').text(dataPayment.nameClient).end().
            find('[data-sumPayments]').text(dataPayment.sumPayments).end().
            modal('show');
            // вызов модального окна просмотра оплат
            // $('#modalViewAllPaymentsToThisOrder').modal('show');

            return false;
        }

        console.log('click по таблице');
    });
    //функция обработки клика в модальном окне будем обрабатывать только кнопку
    $('#modalWinForDeletePayment').on('click',function (event) {
        var target = event.target;
        if(target.name == 'btnDeletePayment'){
            console.log('кликнули кнопку на удаление клиента');
            //будем удалять клиента из базы
            jquery_send('.divForAnswerServer','post','../App/controllers/controllerViewAllClients.php',
                ['deleteClientFromBase','idClient'],['',$('#modalIdPayment').text()]);
            $('#modalIdPayment').text('');
            $('#modalNameClient').text( '');
            $('#modalWinForDeletePayment').modal('hide');

        }
    });
    //функция обработки при вызове модального окна
    $('#modalWinForDeletePayment').on('show.bs.modal',function () {

    });
    //функция поиска платежа по подобию названия клиентов
    $('#btnSearchPClientLikeNameORLikeContactPerson').on('click',function () {
        console.log('нажали кнопку поиска поставщика по подобию названию или добхарактеристик');
        var inputSearchValue = $('#inputFindClient').val();
        if(inputSearchValue.length < 3 || inputSearchValue.length == 0){
            $('#inputFindClient').val('').attr('placeholder','минимум 3 символа');
        }else {
            console.log('отправим запрос на поиск');
            jquery_send('#tbViewAllClients tbody','post','../App/controllers/controllerViewAllClients.php',['searchLike','likeValue'],['',inputSearchValue]);
        }
    });
    //вызов модального окна показа всех оплат по заказу ( нажата кнопка
    //функция просмотра оплат для этого заказа (просмотр оплат в модальном окне)


});