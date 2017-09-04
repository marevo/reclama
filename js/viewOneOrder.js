/**
 * Created by marevo on 10.08.2017.
 */
//повесим на последнюю кнопку с классом клик с вызовом модального окна добавления материалов к заказу
$(function () {
    $('.btnAddMatetialToOrder').on('click', function () {
        // вызов модального окна для добавки материалов к заказу
        if (ORDER.isCompleted == 0) {
            $('#modalAddMaterialToOrder').modal('show');
        }
        else {
            // нельзя добавлять материалы к заказу запустим фунцию подсказку она подсветит на 1 секунду строку подсказки
            $('#rezShow').html('нельзя добавлять материалы -  заказ укомплектован смотрите пункт "комплект" ');
            // в которой стоит запрет на добавление материала и кнопу добавки( в данном случае - ячейку последнюю в tableOneOrder thead tr:last-child)
            promptShow(0, 1000, $('[data-name = "isCompleted"]').parent());
            promptShow(0, 1000, $('#rezShow'));
        }
    });
    $('.btnViewModalWinForMaterialsToThisOrder').on('click', function () {
       $('#modalAddMaterialToOrder').modal('hide');
        $('#modalViewAllMaterialsToThisOrder').modal('show');
    });
});

//*/отказ от udate данных заказа
function clearFildForUpdate(){
    ORDER.nameFieldForUpdate ='';
    ORDER.oldValue = '';
    ORDER.newValue = '';
}
//*отказ от udate данных заказа
//*функция обработки отмены желания сделать  update заказа (запускается 
function refusalUpdate() {
    // $('#forClear').removeClass('forClear').removeAttr('id');
    //возвращаем старое значение в ячейку id='forClear'
    //убираем класс и аттрибут, затем включаем функцию отображения allocateOrderField и потом чистим поля update названия, старое значение, новое значение
    $('#forClear').html( ORDER.oldValue).removeClass('forClear').removeAttr('id');
    allocateOrderField();
    clearFildForUpdate();
}