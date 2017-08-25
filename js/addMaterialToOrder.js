/**
 * Created by marevo on 10.08.2017.
 */
$('#modalAddMaterialToOrder').on('click',function (event) {
    var target = event.target;

    console.log('сработало событие click target= '+target.nodeName +'  target.name= '+target.name);
    if(target.nodeName == 'SPAN' ){
        while (target.nodeName != "BUTTON"){
            target = target.parentNode;//это будет строка где мы кликнули добавить
        }
    }
    if(target.nodeName =='BUTTON'){
        //по нажатию кнопки в таблице с классом class='addMaterialToOrder' будем добавлять материал в таблицу materialsToOrder
        if($(target).hasClass('addMaterialToOrder')){
            // будем добавлять данные в таблицу materialsToOrder idOrder,idMaterials,countNeed
            var idMat = $(target).parent().siblings()['0'].textContent;
            var idOr = ORDER.id; //idOrder
            var tdPrev = $(target).parent().prev();
            var count = $(tdPrev).find('input').val();
            var countTrue = testInputDigit(count);
           //добавление материала к заказу
            /** работаем здесь*/
            if(countTrue) {
                console.log('send to server id= '+idOr +' idMat= '+idMat + ' count= '+count);
                jquery_send('#rezZaprosAddCountMaterial','post','../controllerOneOrder.php',
                    ['addCountMaterialToOrder', 'idMaterial', 'idOrder','countMaterial'],
                    ['',idMat,idOr,count]
                );
                //закрыть окно добавки нужно сделать radioButton если много добавок то не закрывать модальное окно
                // $('#modalAddMaterialToOrder').modal('hide');
            }
            else {
               console.log('ошибка введения количество материала');
            }
            return false;
        }
    }




});

//при показе модального окна для добавки мы запросим данные о всех материалах что есть в базе и выведем их
$('#modalAddMaterialToOrder').on('show.bs.modal',function () {
  //*** вызвать функцию ниже
    getAllMaterialsFromBase();
    //повесим фунцию позаза усеха не успеха обращений на сервер (запросы на изменение)
    herePokazRezZapros($('#rezShowFormAddMaterialToOrder'));
    $('[name = "buttonSearchNameMaterial"]').on('click',serchAllMaterialsForName);
    $('#tbSearchMaterialOnName').on('input',function () {
        var target = event.target;
        console.log('в input ---'+target.nodeName);
        //проверка введения валидного значения в поле input количества материала
        if(target.nodeName == 'INPUT'){
            elementsCssAfterTestInputDigit(target)
            return false;
        }
    });


});
//запрос всех материалов и з базы
function getAllMaterialsFromBase() {
    jquery_send('#tableFildMaterialToAddToOrder','post','../controllerOneOrder.php',
    ['getAllMaterialsFromBase','idOrder'],
    ['', ORDER.id]    
    );
}
//найти материалы по названию (по подобию в названии)
function serchAllMaterialsForName() {
    var nameMater = $.trim($('#idInputNameMaterial').val());
    if(nameMater != ""){
        console.log('посылаем вызов в базу с nameMater:'+nameMater);
        jquery_send('#tbSearchMaterialOnName','post','../controllerOneOrder.php',
            ['searchMaterialsForName','nameMaterialLike'],['',nameMater]
        );
    }
    return false;
}
