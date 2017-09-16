/**
 * Created by marevo on 14.09.2017.
 */

$('#modalViewAllPaymentsToThisOrder').on('show.bs.modal',function (idOrder) {
    //отправляем запрос на загрузку данных всех оплат из базы в таблицу #tableAllPaymentsForThisOrder
    console.log('отпавим запрос на получение данных всех оплат по idOrder='+
        $(this).find('[data-idOrder]').text());
    jquery_send('.divForAnswerServer', 'post', '../app/controllers/controllerModalWinShowAllPayments.php',
        ['loadPaymentForOrderFromViewAllPayments', 'idOrder'], ['', $(this).find('[data-idOrder]').text()]);
    //выставим дату сегодняшнюю ее берет js c клиента
    //var dateThisDay = getDate();
    $('#idModalWinDatePayment').val(getDate());
    //повесим проверку на валидность суммы оплаты
    povesitProverkuValidnostyNaInput('#idModalWinValPayment');

    //Добавка оплаты через модальное окно просмотра всех оплат по данному заказу
    $('#idModWinBtnAddPayment').on('click', addPaymentInModalWinAllPayments);

//    вешаем клик на отобрыжение кнопок удаления в модальном окне где показаны платежи по заказу
    $('#btnModalShowButtonsTrashInTable').on('click', showButtonsTrashInTableAllPayments);

//повесим клик на удаление конкретного платежа в модальном окне таблицы платежей по заказу
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
// добавка в базу нового платежа по заказу, платежи которого показаны в модальном окне всех платежей по кликнутому заказу
function addPaymentInModalWinAllPayments() {
    console.log(' пробуем добавить оплату добавляем оплату по этому заказу');
//если сумма добавки 0 или отрицательна или заказ закрыт успешно или закрыт не успешно то не сможем добавить заказ
    if($('#idModalWinValPayment').val() > 0 ){
        //  отправляем запрос на добавку в базу оплаты
        console.log('добавляем оплату по этому заказу');
        jquery_send('#modalViewAllPaymentsToThisOrder .divShowAnswerServer','post','../app/controllers/controllerModalWinShowAllPayments.php',
            ['sendPaymentForOrderFromModalWinFromViewAllPayments','sumPayment','idOrder','idClient','datePayment'],
            ['',$('#idModalWinValPayment').val(),
                $('#modalViewAllPaymentsToThisOrder').find('[data-idorder]').text() ,
                $('#modalViewAllPaymentsToThisOrder').find('[data-idclient]').text() ,
                $('#idModalWinDatePayment').val()]);
        $('#idModalWinValPayment').val('0');
        console.log('улетели данные на добавку оплаты в базу idOrder')
    }
    else {
        $('#idModalWinValPayment').val('0');
        fNoUspehAll('нельзя добавить оплату, т.к. сумма добавки 0');
    }

    return false;
}
//функция удаления оплаты из базы оплат срабатывает в модальном окне при клике красного значка удалить
function     deleteThisPaymentFromBase (event) {
    var target = event.target;
    while (target.tagName !='TABLE'){
        if(target.tagName == 'BUTTON'){
            if( $(target).hasClass('btnDeleteThisPayment') ){
                console.log('click in table target=button trash');
                var idPaymentForDelete = $(target).data('id');
                console.log('хотим удалить платеж с id = '+ idPaymentForDelete);
                jquery_send('#modalViewAllPaymentsToThisOrder .divShowAnswerServer','post','../app/controllers/controllerModalWinShowAllPayments.php',
                    ['sendDeletePaymentForOrderFromModalWinFromViewAllPayments','idPaymentForDelete'],
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
            // var idOrder = $(target).data('idorder');
            var dataPayment = $(target).data('payment');
            console.log('click on button for viewAllPaymentsForThisOrder with idOrder='+ dataPayment.idOrder);
            // занесем даный idOrder  в модальное окно что бы при его показе подгрузить все оплаты по этому id
            $('#modalViewAllPaymentsToThisOrder').
            find('[data-idorder]').text(dataPayment.idOrder).end().
            find('[data-nameorder]').text(dataPayment.nameOrder).end().
            find('[data-idclient]').text(dataPayment.idClient).end().
            find('[data-nameclient]').text(dataPayment.nameClient).end().
            find('[data-sumpayments]').text(dataPayment.sumPayments).end().
            modal('show');
            // вызов модального окна просмотра оплат
            // $('#modalViewAllPaymentsToThisOrder').modal('show');

            return false;
        }

        console.log('click по таблице');
    });

    //функция обработки при вызове модального окна
    $('#modalWinForDeletePayment').on('show.bs.modal',function () {

    });
    //функция поиска платежа по подобию названия клиентов или по дате или по клиенту и дате
    $('#btnSearchPaymentForClient').on('click',function () {
        console.log('нажали кнопку поиска платежа в блоке поиска на странице viewAllPayments');
        //объект поле Input Для ввода имени клиента 
        var $inputSearchNameClientValue = $('#inputFindPaymentForNameClient');
        //значение в поле input клиента
        var inputSearchNameClientValue = $inputSearchNameClientValue.val();
        
        var $dateFrom = $('#inputFindPaymentForDatePaymentFromDate');
        var $dateTo = $('#inputFindPaymentForDatePaymentToDate');
        if($dateFrom.val()== "" && $dateTo.val() == ""){
            console.log('нет выбора по датам пошле запрос поиска проплат по назнанию клиента');
            if(inputSearchNameClientValue.length < 3 || inputSearchNameClientValue.length == 0){
                $inputSearchNameClientValue.val('').attr('placeholder','минимум 3 символа');
                var sI = setTimeout(function () {
                    $inputSearchNameClientValue.val('').attr('placeholder','имя клиента');
                    clearTimeout(sI);
                },1500);
            }else {
                console.log('отправим запрос на поиск платежей только по подобию названию клиента');
                 jquery_send('.divForAnswerServer','post','../App/controllers/controllerViewAllPayments.php',['searcPaymentshLikeNameClient','likeValue'],['',inputSearchNameClientValue]);
            }
        }
    });
    //вызов модального окна показа всех оплат по заказу ( нажата кнопка
    //функция просмотра оплат для этого заказа (просмотр оплат в модальном окне)


});

function modalAllPaymentsForThisOrder_HIDE_SHOW() {
    $('#modalViewAllPaymentsToThisOrder').modal('hide');
    var sTH_S = setTimeout(function () {
        $('#modalViewAllPaymentsToThisOrder').modal('show');
        clearTimeout(sTH_S);
    },1000);
}