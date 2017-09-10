/**
 * Created by marevo on 03.08.2017.
 */
//обработка для модального окна просмотра и добавления материалов к заказу viewAllMaterialsToOrder.html
$('#modalViewAllMaterialsToThisOrder').on('click',function (event) {
    var target = event.target;
    if(target.name == 'ModalClose' || target.name == 'ModalClose2' ){
        $('#modalViewAllMaterialsToThisOrder').modal('hide');
    }
    if(target.name == 'sumMatZakaz'){
        console.log('обработка суммы материалов заказа');
        // ORDER.oldValue = ORDER.manufacturingPrice;
        var sumManufacturingPrice = $('[name="valManufacturingPrice"]').val();
        if(testSumOnFloat(sumManufacturingPrice) && +sumManufacturingPrice >= 0 ){
            ORDER.oldValue = ORDER.manufacturingPrice;
            ORDER.newValue = sumManufacturingPrice;
            ORDER.nameFieldForUpdate = 'manufacturingPrice';
            console.log('ORDER.nameFieldForUpdate = '+ ORDER.nameFieldForUpdate +
                '  ORDER.oldValue = '+ ORDER.oldValue +
                '  ORDER.newValue = '+ ORDER.newValue);

            //измененеие суммы комплектующих *** почему-то не работает при повторной отправке
            if(ORDER.oldValue != ORDER.newValue){
                jquery_send('.divForAnswerServer','get','../controllerOneOrder.php',
                    ['update','nameField','valueField','idOrder'],
                    ['',ORDER.nameFieldForUpdate,ORDER.newValue,ORDER.id]
                );
            }
        }
        else{
            $('[name="valManufacturingPrice"]').removeClass('orderSuccessCell').addClass('orderNoSuccessCell');
            ORDER.newValue = '';
        }
    }
    if(target.nodeName == 'SPAN'){
        while (target.nodeName !='BUTTON' && target.nodeName !='TD'){
            target = target.parentNode;
        }
        if(target.nodeName =='BUTTON' && target.name == 'addMaterial'){
            console.log('нажали кнопку добавки материала будем закрывать это модальное окно и вызовем другое модальное окно по добавке материала ');
            $('#modalViewAllMaterialsToThisOrder').modal('hide');
            $('#modalAddMaterialToOrder').modal('show');
        }

    }
   console.log('нажали одну из кнопок ');
});


$('#submitNoModalForm').on('click',function () {
    console.log('нажали кнопку передумал в форме добавки материала');
});
//при показе модального окна мы запросим данные о всех материалах для этого заказа
$('#modalViewAllMaterialsToThisOrder').on('show.bs.modal',function () {
    //при вызове модального окна запустим фунцию нахождения всех материалов к заказу
    getAllMaterialsForOrder();
    //повесим фунцию позаза усеха не успеха обращений на сервер (запросы на изменение)
    herePokazRezZapros( $('#rezShowModal'));
//повесим фунцию проверки на валидность введенной суммы за материалы
    povesitProverkuValidnostyNaInput($('[name="valManufacturingPrice"]'));
//повесим клик по таблице всех материалов к заказу чтобы можно было удалить материал, или изменить его количество, или добавить такой же
    $('#tableAllMaterialsForOrder').on('click',clickTableAllMaterialsForOrder);
//повесим фунцию позаза усеха не успеха обращений на сервер (запросы на изменение)
    herePokazRezZapros( $('#rezDeleteTr'));
});
// когда модальное окно отобажено повесим на input все проверку валидности
$('#modalViewAllMaterialsToThisOrder').on('shown.bs.modal',function () {
    // повесим на input все проверку валидности
    $('#tableAllMaterialsForOrder input').each(function () {
        povesitProverkuValidnostyNaInput($(this));
    });

});
//обработка click в таблице tableAllMaterialsForOrder
function clickTableAllMaterialsForOrder() {
    var target = event.target;
    console.log('кликнули в модальном окне таблице id=tableAllMaterialsForOrde rвсех материалов в заказе');
    // проверим был клик в input
    //*** правка количества материала в заказе
    if(target.nodeName =='INPUT'){
        console.log('вы кликнули в input изменения количества материала в заказе');
    }
    var trClickInTableAllMaterialsForOrder;//строка где был клик
    if(target.nodeName !='TR'){
        while (target.nodeName !='TD'){
            target = target.parentNode;
        }
        trClickInTableAllMaterialsForOrder = target.parentNode;
        //если это нажата ячейка с классом для удаления материала
        if($(target).hasClass('deleteThisMaterialFromThisOrder')){
            // к строке где был клик на удаление материала в заказе добавим класс, по которому (в случае успеха) будем удалять саму строку
            $(trClickInTableAllMaterialsForOrder).addClass('deleteThisTr');

            var idMaterialToOrder = $(target).siblings()[0].textContent;
            console.log('id for delete = '+idMaterialToOrder);
            jquery_send('.rezDeleteTr','post','../controllerOneOrder.php',
            ['deleteThisMaterialFromOrder','idMaterialToOrder','idOrder'],
                ['',idMaterialToOrder,ORDER.id]);
            return false;
        }
        if($(target).hasClass('updateThisCountMaterial')){
            // к строке где был клик на update материала в заказе добавим класс, по которому (в случае успеха) будем изменять саму строку
            $(trClickInTableAllMaterialsForOrder).addClass('updateCountMaterialToOrder');
// не забыть удалить класс в этой строке для того, чтобы избежать ошибок с другими строками
            console.log('нажали на кнопку изменить в количестве материала');
            var idMaterialToOrder = $(target).siblings()[0].textContent;
            var countMatNew = $(target).prev().find('input').val();
            // $(target).prev().find('input').css('border','1px solid red');
            // если не пусто в input для update и не равны суммы до и после update
            if(countMatNew && +countMatNew!= +$(target).siblings()[4].textContent){
                console.log('id for update = '+idMaterialToOrder + 'countMatNew = '+countMatNew);
                jquery_send('.divForAnswerServer','post','../controllerOneOrder.php',
                    ['updateThisCountMaterialsForOrder','idMaterialToOrder','countMatNew'],
                    ['',idMaterialToOrder,countMatNew]
                );
                //сервер если успешно или не успешно должен должен прислать скрипт на удаление класса у этой строки, но только изменения в ней данных
            }else {
                fNoUspeh();
                //так как не удалось отправить на update запись в таблице материалов для этого заказа нужно убрать класс-маркер 
                $(trClickInTableAllMaterialsForOrder).removeClass('updateCountMaterialToOrder');
            }
            return false;
        }
    }

}
//при сокрытии модального окна
$('#modalViewAllMaterialsToThisOrder').on('hide.bs.modal',function () {
    //уберем красную окантовку вокруг кликнутых ячеек
    $('#forClear').removeClass('forClear').removeAttr('id');
    //очистим таблицу всех материалов
    $('#tableAllMaterialsForOrder').html('');
});

//вызов функции для запроса всех материалов в этом заказе для показа их в модальном окне
function getAllMaterialsForOrder() {
    jquery_send('#tableAllMaterialsForOrder','post','../controllerOneOrder.php',
        ['getAllMaterialsForOrder','idOrder'],
        ['', ORDER.id]
    );
}
