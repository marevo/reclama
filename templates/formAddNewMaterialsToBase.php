<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 03.08.2017
 * Time: 23:23
 */
require '../autoload.php';
//функция подгрузки из базы поставщиков по имени
function suppliersOptions(){
    $option='';
    $suppliersAll = \App\Models\Supplier::findAll();
    foreach ($suppliersAll as $rowItem){
        $option .= "<option data-id = '$rowItem->id' value='$rowItem->id'>$rowItem->name ... $rowItem->addCharacteristic</option>";
    }
    return $option;
}
?>
<!DOCTYPE HTML>
<html lang="ru-RU">
<?php include('../head.html') ?>
<body>
<div class="container">
    <div class="row">
        <?php require_once('header.html'); ?>
    </div>
    <div class="row"><!-- навигация -->
        <?php include('../navigation.html');?>
        <script>
            showLi('добавить материалы')
        </script>
        <!-- конец навигации -->
    </div>
    <div class="row">
        <div class="col-lg-2 backForDiv">
            этот див слева от таблицы в нем можно расположить дополнительные кнопки добавить редактировать удалить
        </div>
        <div class="col-lg-10 backForDiv">
            <!--строка показа времени и показа результата добавки материала в базу  -->
            <?php  include_once '../App/html/forDisplayTimeShowAnswerServer.html'?>
            <div class="row">
                <div class="col-lg-12   col-md-12 col-sm-12 col-xs-12 bg-primary  h2 text-center text-info">добавление материалов в базу</div>

            </div>
            <div class="row"><!--форма добавки материала -->
                <div class="col-lg-6">
                    <form  id="formOneMaterial"   method="post" action="../App/controllers/controllerAddNewMaterialsToBase.php" >
                        <table>
                            <thead><tr>
                                <td>название поля</td>
                                <td>значение поля</td></tr></thead>
                            <tbody>
                                <tr><td class="text-right"><label for="idSupplier">поставщик</label></td>
                                    <td><select name="idSupplier" ><option value="0"> поставщик ... что поставляет</option>
                                            <?php echo suppliersOptions();  ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr style="display: none;">
                                    <td class="text-right"><label for="send">скрытое поле  для отправки маркера</label></td>
                                    <td class="text-left"><input  name="send"  value="sendMarker"  /></td>
                                </tr>
                                <tr><td class="text-right"><label for="nameMaterial">название материла</label></td>
                                    <td class="text-left"><textarea cols="50" rows="4" name="nameMaterial" placeholder="введите название материала" required ></textarea></td>
                                </tr>
                                <tr><td class="text-right"><label for="addCharacteristic">дополнительные характеристики</label></td>
                                    <td class="text-left"><textarea cols="50" rows="4" name="addCharacteristic" placeholder="поставка рулоном по 4 м или режут газом по 2.86 м" required ></textarea></td>
                                </tr>
                                <tr><td class="text-right"><label for="measure">единицы измерения</label></td>
                                    <td><input type="text" name="measure" placeholder="м или м погонный" required/> </td></tr>
                                <tr><td class="text-right"> <label for="deliveryForm">форма поставки</label></td>
                                    <td><input type="text" name="deliveryForm" placeholder="2.86 или 4.00 " required/> </td></tr>
                                <tr><td class="text-right"><label for="priceForMeasure">цена за единицу поставки <br/>за метр погонный</label></td>
                                    <td><input type="text" name="priceForMeasure" value="00.00" pattern="\d{1,7}(\.|,)\d{2}" placeholder="введите цену заказа"/></td>
                                </tr>
                                <tr><td class="text-right"></td><td><input type="submit"  name="submitFromFormOneMaterial"/></td>
                                </tr>

                            </tbody>
                        </table>
                    </form>
<script>
$('form select').on('change',function () {
if($(this).val() == 0) {
    $('.alert .alert-info').remove();
    $(this).before('<div class="alert alert-info">выберитите поставщика из выпадающего списка</div>');
    return false;
}
else
    $('.alert').remove();
return false;
});
$('form textarea').on('blur',function () {
$(this).val($.trim($(this).val()));
console.log('убрали пробелы');
if($(this).val != ''){
    $('.alert').remove();
}
return false;
});
$('form').submit(function () {

if ($(this).find('select').val() == 0) {
    $(this).find('.alert').remove();
    $(this).find('select').before('<div class="alert alert-info">выберитите поставщика из выпадающего списка</div>');
    return false;
}
if ($(this).find('textarea').val() == 0) {
    $(this).find('textarea').before('<div class="alert alert-info">обязательные поля для заполнения</div>');
    return false;
}
//                            $.post(
//                                $(this).attr('action'),//ссылка куда идут данные
////                                $(this).serialize() ,   //Данные формы
//                                $(this).serializeArray(),//сериализирует в виде массива
//                            );
$.ajax({
    type: $(this).attr('method'),
    url: $(this).attr('action'),//ссылка куда идут данные,
    data: $(this).serializeArray(),//сериализирует в виде массива
    success: function ( data) {
//                                     fUspehAll('удачно');
        $('.divForAnswerServer').html(data);
//                                     return false;
//                                    $(this).find('.alert').remove();
//        alert('улетели данные ' + $(this).serializeArray());
        console.log($(this).serializeArray());
    }

});
$(this).find('.alert').remove();
return false;
});
</script>
                </div>
            </div><!-- .row -->
        </div>
    </div><!-- .row -->
</div><!-- .container -->
