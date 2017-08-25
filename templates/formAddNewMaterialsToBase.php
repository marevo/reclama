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
//функция вставки в базу нового материала
function insertNewMaterialToBase()
{
    if (isset($_POST['submitFromFormOneMaterial'])) {
        echo 'пришел запрос на материала в базу <br/>';
        $matNew = new \App\Models\Material();
        if (isset($_POST['nameMaterial'])) {
            $matNew->name = trim( htmlspecialchars($_POST['nameMaterial']) );
        }
        if (isset($_POST['addCharacteristic'])) {
            $matNew->addCharacteristic = trim( htmlspecialchars($_POST['addCharacteristic']));
        }
        if (isset($_POST['idSupplier'])) {
            $matNew->id_suppliers = intval($_POST['idSupplier']);
        }
        if (isset($_POST['measure'])) {
            $matNew -> measure =trim( htmlspecialchars($_POST['measure']));
        }
        if (isset($_POST['deliveryForm'])) {
            $matNew -> deliveryForm = trim( htmlspecialchars($_POST['deliveryForm']));
        }
        if(isset($_POST['priceForMeasure'])){
            $matNew -> priceForMeasure = trim(htmlspecialchars($_POST['priceForMeasure']));
        }
        //вставим новый заказ в базу
        $resInsert = $matNew -> insert();
        if($resInsert != false){
            echo '<br/>USPESHNO добавлен материал <br/>';
        }
        else{
            echo"!!!ошибка в добавлении материала в базу";
        }
//        if (isset($_POST['submitFromFormOneOrder']))
//            foreach ($orNew as $k => $value) {
//                echo "<br/>$k--- $value";
//            }
    }
}
if (isset($_POST['submitFromFormOneMaterial'])){
    insertNewMaterialToBase();
}
?>
<!DOCTYPE HTML>
<html>
<?php include('../head.html') ?>
<body>
<div class="container">
    <div class="row">
        <?php require_once('header.html'); ?>
    </div>
    <div class="row"><!-- навигация -->
        <?php include('../navigation.html');?>
        <script>
            showLi('создать заказ')
        </script>

    </div>
    <div class="row">
        <div class="col-lg-2 backForDiv">
            этот див слева от таблицы в нем можно расположить дополнительные кнопки добавить редактировать удалить
        </div>
        <div class="col-lg-10 backForDiv">
            <div class="row"><!--форма добавки материала -->
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 bg-primary  h2 text-center text-info">добавление заказа</div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-center rezShow ">результат добавления </div>
                <?php
                //обработка добавления материала в базу
                ?>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <form  id="formOneMaterial"   method="post" >
                        <table>
                            <thead><tr>
                                <td>название поля</td>
                                <td>значение поля</td></tr></thead>
                            <tbody>
                            <!--для проверки input pattern -->
                                <tr><td>кнопка для проверки отправки</td><td><input type="submit" name="submitFromFormOneMaterial"/></td></tr>
                                <tr><td><label for="idSupplier">поставщик</label></td>
                                    <td><select name="idSupplier"  required ><option value="0"> поставщик ... что поставляет</option>
                                            <?php echo suppliersOptions();  ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr><td class="text-right"><label for="nameMaterial">название материла</label></td>
                                    <td class="text-left"><textarea type="text" name="nameMaterial"
                                                                    placeholder="введите название материала"
                                                                    style="min-height: 50px;" autofocus required>
                                            </textarea>
                                    </td>
                                </tr>
                                <tr><td class="text-right"><label for="addCharacteristic">дополнительные характеристики</label></td>
                                    <td class="text-left"><textarea type="text" name="addCharacteristic"
                                                                    placeholder="в виде: поставка рулоном по 4 м или так:режут газом по 2.86 м"
                                                                    style="min-height: 100px;"  required>
                                            </textarea></td>
                                </tr>
                                <tr><td> <label for="measure">единицы измерения</label></td>
                                    <td><input type="text" name="measure" placeholder="м или м погонный" required/> </td></tr>
                                <tr><td> <label for="deliveryForm">форма поставки</label></td>
                                    <td><input type="text" name="deliveryForm" placeholder="2.86 или 4.00 " required/> </td></tr>

                                <tr><td><label for="priceForMeasure">цена за единицу поставки <br/>за метр погонный</label></td>
                                    <td><input type="text" name="priceForMeasure" value="00.00" pattern="\d{1,7}(\.|,)\d{2}" placeholder="введите цену заказа"/></td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                    <script>
                        $('form').submit(function(){
                            $(this).find('.alert .alert-info').remove();
//проверка данных отправляемых с формы
                            if($(this).find('select').val() == 0){
                                $(this).find('select').before('<div class="alert alert-info">выберитите поставщика из выпадающего списка</div>');
                                return false;
                            }
//                               return;
//                            return false;
//                            $url = $(this).attr('action');
//                            $data=$(this).serializeArray();
                            $.post(
                                $(this).attr('action'),//ссылка куда идут данные
//                                $(this).serialize() ,   //Данные формы
                                $(this).serializeArray()//сериализирует в виде массива
                            );
//                            return false;
//                            $.ajax({
//                                type:'POST',
//                                url:$url,
//                                data:$data,
//                                success:function () {
//                                    alert('улетели данные '+$(this).serializeArray())
//                                }
//                            });
                        });
                    </script>
                </div>
            </div><!-- .row ->
        </div>
    </div>
</div>
