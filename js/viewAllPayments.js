/**
 * Created by marevo on 14.09.2017.
 */

// выбор даты в первом поле "от"  после выбора даты в первом поле если есть дата в первом поле больше чем во втором, то во втором установим такое же как и в первом
$('#inputFindPaymentForDatePaymentFromDate').on('input',function () {
    //при выборе даты от автоматом ставится сегодняшняя дата до при обнулении от обнуляется и до
    if($(this).val()>$('#inputFindPaymentForDatePaymentToDate').val() )
        $('#inputFindPaymentForDatePaymentToDate').val($(this).val());
    // else
    //    $('#inputFindPaymentForDatePaymentToDate').val(getDate());
});
// выбор даты во втором поле если после выбора даты во втором поле дата будет меньше чем в первом, то установим дату в первом поле
$('#inputFindPaymentForDatePaymentToDate').on('input',function () {
    //при выборе даты от автоматом ставится сегодняшняя дата до при обнулении от обнуляется и до
    if($(this).val() < $('#inputFindPaymentForDatePaymentFromDate').val() )
        $('#inputFindPaymentForDatePaymentFromDate').val($(this).val());
});

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
    //функция обработки клика на таблице всех оплат (будем искать клик на кнопке просмотр - стоит справа в каждой строке )
    $('#tbViewAllPayments').on('click', function (event) {
        var target = event.target;
        if (target.nodeName == 'SPAN' && target.parentNode.nodeName == "BUTTON")
            target = target.parentNode;
        if (target.nodeName == 'BUTTON' && target.name == 'btnViewModalAllPaymentThisOrder') {
            var idOrder = $(target).data('idorder');
            console.log('click on button for viewAllPaymentsForThisOrder with idOrder=' + idOrder);
            // занесем даный idOrder  в модальное окно что бы при его показе подгрузить все оплаты по этому id
            var $modalViewAllPaymentsToThisOrder = $('#modalViewAllPaymentsToThisOrder');
            $modalViewAllPaymentsToThisOrder.find('[data-idOrder]').text(idOrder) ;
            $modalViewAllPaymentsToThisOrder.find('[data-nameOrder]').text($(target).parent().siblings()[3].textContent) ;
            $modalViewAllPaymentsToThisOrder.find('[data-idClient]').text($(target).parent().siblings()[0].textContent) ;
            $modalViewAllPaymentsToThisOrder.find('[data-nameClient]').text($(target).parent().siblings()[1].textContent) ;
            $modalViewAllPaymentsToThisOrder.find('[data-sumPayments]').text($(target).parent().siblings()[4].textContent) ;
            $modalViewAllPaymentsToThisOrder.modal('show');
            // вызов модального окна просмотра оплат
            // $('#modalViewAllPaymentsToThisOrder').modal('show');
            return false;
        }
        console.log('click по таблице показа оплат на странице viewAllPayments.php');
    });

    //повесим на кнопку добавка оплаты вызов модального окна modalFormAddNewPaymentToBase.html для поиска заказа и добавки оплаты к нему
    $('#makeNewPaymentFromModalFormAddNewPayment').on('click',function (event) {
        $('#modalAddPaymentToBaseForViewAllPayments').modal('show');
        return false;
    });
    //функция обработки при вызове модального окна
    $('#modalWinForDeletePayment').on('show.bs.modal', function () {

    });
    // функция проверки на валидность поля поиска имени если правильное вернет это имя, если нет вернет false
    function trueLikeName() {
        //объект поле Input Для ввода имени клиента
        var $inputSearchNameClientValue = $('#inputFindPaymentForNameClient');
        //значение в поле input клиента
        var inputSearchNameClientValue = $inputSearchNameClientValue.val();
        if (inputSearchNameClientValue.length < 3 || inputSearchNameClientValue.length == 0) {
            // в поле имя нет 3 символов вышлем подсказку и выйдем из поиска
            $inputSearchNameClientValue.val('').attr('placeholder', 'минимум 3 символа');
            var sI = setTimeout(function () {
                $inputSearchNameClientValue.val('').attr('placeholder', 'имя клиента');
                clearTimeout(sI);
            }, 1500);
            return false;
        } else {
            return $inputSearchNameClientValue.val();
        }
    }

    //функция поиска платежа по подобию названия клиентов или по дате или по клиенту и дате
    $('#btnSearchPaymentForClient').on('click', function () {
        console.log('нажали кнопку поиска платежа в блоке поиска на странице viewAllPayments');
        var dateFrom = $('#inputFindPaymentForDatePaymentFromDate').val();
        var dateTo = $('#inputFindPaymentForDatePaymentToDate').val();
        var likeName = trueLikeName();
        //пройдем по блок-схеме запроса оплат
        if (dateFrom && dateTo) {
            // 2 даты
            if (likeName) {
                //2 даты , имя
                //2 - getPaymentsClientsOrdersSumPaymentsCountPaymantsLikeNameDateFromDateTo($likeNameClient, $dateFrom, $dateTo);
                console.log('отправим запрос на поиск платежей по датам и  по подобию названию клиента');
                jquery_send('.divForAnswerServer', 'post', '../App/controllers/controllerViewAllPayments.php',
                    ['searchPaymentsLikeNameClientDateFromDateTo', 'likeNameClient', 'dateFrom', 'dateTo'],
                    ['', likeName, dateFrom, dateTo]);
                return false;
            } else {
                //2 даты
                //3 - getPaymentsClientsOrdersSumPaymentsCountPaymantsDateFromDateTo($dateFrom, $dateTo);
                jquery_send('.divForAnswerServer', 'post', '../App/controllers/controllerViewAllPayments.php',
                    ['searchPaymentsDateFromDateTo', 'dateFrom', 'dateTo'],
                    ['', dateFrom, dateTo]);
                return false;
            }
        } else {
            //только 1 дата
            if (dateFrom || dateTo) {
                // выбрано 1 дату dateFrom
                if (dateFrom) {
                    //дата "от"
                    if (likeName) {
                        //дата "от" "имя"
                        //4 - getPaymentsClientsOrdersSumPaymentsCountPaymantsDateMoreDateFromAndLikeName($dateFrom, $likeName);
                        jquery_send('.divForAnswerServer', 'post', '../App/controllers/controllerViewAllPayments.php',
                            ['searchPaymentsDateMoreDateFromAndLikeName', 'dateFrom', 'likeNameClient'],
                            ['', dateFrom, likeName]);
                        return false;
                    } else {
                        //дата "от"
                        //5 - getPaymentsClientsOrdersSumPaymentsCountPaymantsDateMoreDateFrom($dateFrom);
                        jquery_send('.divForAnswerServer', 'post', '../App/controllers/controllerViewAllPayments.php',
                            ['searchPaymentsDateMoreDateFrom', 'dateFrom'],
                            ['', dateFrom]);
                        return false;
                    }

                } else {
                    // дату dateTo
                    if (likeName) {
                        // дату "до" "имя"
                        //6 - getPaymentsClientsOrdersSumPaymentsCountPaymantsDateLessDateToAndLikeName($dateTo, $likeName)
                        jquery_send('.divForAnswerServer', 'post', '../App/controllers/controllerViewAllPayments.php',
                            ['searchPaymentsDateLessDateToAndLikeName', 'dateTo', 'likeNameClient'],
                            ['', dateTo, likeName]);
                        return false;
                    } else {
                        //только "до"
                        //7 - getPaymentsClientsOrdersSumPaymentsCountPaymantsDateLessDateTo($dateTo)
                        jquery_send('.divForAnswerServer', 'post', '../App/controllers/controllerViewAllPayments.php',
                            ['searchPaymentsDateLessDateTo', 'dateTo'],
                            ['', dateTo]);
                        return false;
                    }
                }
            } else {
                // только имя
                //1 - getPaymentsClientsOrdersSumPaymentsCountPaymantsLikeName($likeNameClient)
                jquery_send('.divForAnswerServer', 'post', '../App/controllers/controllerViewAllPayments.php',
                    ['searchPaymentsLikeName', 'likeNameClient'],
                    ['', likeName]);
                return false;
            }
        }
    });
    //*/функция поиска платежа по подобию названия клиентов или по дате или по клиенту и дате
});
//перезагрузка показанного модального окна для обновления результатов добавления-удаления оплат
function modalAllPaymentsForThisOrder_HIDE_SHOW() {
    $('#modalViewAllPaymentsToThisOrder').modal('hide');
    var sTH_S = setTimeout(function () {
        $('#modalViewAllPaymentsToThisOrder').modal('show');
        clearTimeout(sTH_S);
    },1000);
}