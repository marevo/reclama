<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 27.08.2017
 * Time: 23:17
 */
require '../autoload.php';
if(isset($_GET['id'])){
//    если передали id значит работаем с ним иначе будем брать в else по умолчанию id=1
    $idMaterial = intval($_GET['id']);
}
else{
    $idMaterial = 1;
}
$mat = \App\Models\Material::findObjByIdStatic($idMaterial);
//все поставщики для выбора поставщика при update
$allSuppliers = \App\Models\Supplier::getAllSuppliers();
$options = "";
foreach ($allSuppliers as $item){
    $options.= "<option value='$item->id' >$item->name ... $item->addCharacteristic</option>";
}
?>
    <!DOCTYPE HTML>
    <html lang="ru-RU">
<title> просмотр/правка данных материала </title>
    <?php
    require_once('../head.html');
    ?>
    <body>
    <div class="container">
        <div class="row">
            <?php require_once('header.html'); ?>
        </div>
        <div class="row"><!-- навигация -->
            <?php require_once('../navigation.html');?>
            <script>
                showLi('материал');
            </script>
            <!-- конец навигации -->
        </div>
        <div class="row">
            <!--            начало доп блока слева-->
            <div class="col-lg-2 backForDiv">
                этот див слева от таблицы в нем можно расположить дополнительные кнопки добавить редактировать удалить
            </div>
            <!--            конец доп блока слева-->
            <div class="col-lg-10 backForDiv">
                <!--строка показа времени и показа результата добавки материала в базу  -->
                <?php  include_once '../App/html/forDisplayTimeShowAnswerServer.html'?>
                <div class="row headingContent">
                    <div class="col-lg-10   col-md-10 col-sm-10 col-xs-10   text-center ">правка материала <?php echo $mat->name;?></div>
                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center"><button id="btnUpdateShow" > обновить </button></div>
                    <?php
                    $ifExistOrderWithThisMaterial = \App\Models\MaterialsToOrder::ifExistThisMaterialInAnyOneOrder($mat->id);
                    if(! $ifExistOrderWithThisMaterial){
                        //нет материалов этого поставщика ни в одном заказе кнопка править
                        echo "<div class='col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center'><button class='btn btn-sm btn-primary' id='btnEnableUpdate' >править</button></div>";
                    }
                    else{
                        //есть материалы этого поставщика, значит его править нельзя
                        echo "<div class='col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center'><button disabled='true' class='btn btn-sm btn-primary' id='btnEnableUpdate' >править</button></div>";
                    }
                    ?>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <form action="../App/controllers/controllerOneMaterial.php" method="post">
                        <table>
                            <thead>
                            <tr>
                                <td>название поля</td>
                                <td>значение</td>
                                <td>новое значение</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr style="display: none;"><td>id</td><td><?php echo $mat->id ?></td><td class="formMaterial"><input  name="id" type="text" value="<?php echo $mat->id ?>"/></td></tr>
                            <tr><td>название</td><td><?php echo $mat->name ?></td><td class="formMaterial"><input name="name" type="text" size="55" maxlength="200" title ="<?php echo $mat->name ?>" value="<?php echo $mat->name ?>"/></td></tr>
                            <tr><td>дополнительные сведения</td><td><?php echo $mat->addCharacteristic ?></td><td class="formMaterial"><input maxlength="200" size="55" name="addCharacteristic" title="<?php echo $mat->addCharacteristic ?>" type="text" value="<?php echo $mat->addCharacteristic ?>"/></td></tr>
                            <tr><td>единица измерения</td><td><?php echo $mat->measure ?></td><td class="formMaterial"><input name="measure" maxlength="50" type="text" value="<?php echo $mat->measure ?>"/></td></tr>
                            <tr><td>форма поставки</td><td><?php echo $mat->deliveryForm ?></td><td class="formMaterial"><input pattern="\d{1,4}(\.)?\d{1,2}" name="deliveryForm" type="text" value="<?php echo $mat->deliveryForm ?>"/></td></tr>
                            <tr><td>цена за единицу</td><td><?php echo $mat->priceForMeasure ?></td><td class="formMaterial"><input pattern="\d{1,4}(\.)?\d{1,2}" name="priceForMeasure" type="text" value="<?php echo $mat->priceForMeasure ?>"/></td></tr>
                            <tr><td>id поставщика</td><td><?php echo  \App\Models\Supplier::findObjByIdStatic($mat->id_suppliers)->name;  ?></td><td class="formMaterial"><select name="id_suppliers" type="text"><?php echo $options ?></select></td></tr>
<!--                            <tr><td>название поставщика</td><td>--><?php //echo \App\Models\Supplier::findObjByIdStatic($mat->id_suppliers)->name; ?><!--</td><td class="formMaterial"><input name="name_suppliers" type="text"/></td></tr>-->
                            <tr><td></td><td></td><td class="formMaterial"><input name="name_suppliers" type="submit"/></td></tr>
                            <tr style="display: none;"><td>скрытое поле</td><td>маяк</td><td><input name="submitOneMaterial" /></td></tr>
                            </tbody>
                        </table>
                        </form>
                    </div>
                </div>
                <table></table>
            </div>
        </div>
    </div>
    <script type="text/javascript">

        $(function () {
            $('#btnUpdateShow').on('click',function () {
                location.reload();
            });
            $('#btnEnableUpdate').on('click',function () {
                $('.formMaterial').each(function () {
                    $(this).css('display',function (i,value) {
                        if(value == 'block')
                            return 'none';
                        else return 'block';
                    });
                });
            });
            $('select').val('<?php echo $mat->id_suppliers ;?>');

        });
        $('form').submit(function () {
            $.ajax({
                type: $(this).attr('method'),
                url: $(this).attr('action'),//ссылка куда идут данные,
                data: $(this).serializeArray(),//сериализирует в виде массива
                success: function ( data) {
//                                     fUspehAll('удачно');
                    $('.divForAnswerServer').html(data);
//                                     return false;
//                                    $(this).find('.alert').remove();
//                    alert('улетели данные ' + $(this).serializeArray());
                    console.log($(this).serializeArray());
                }
            });
            $(this).find('.alert').remove();
            return false;
        });
    </script>
    </body>
    </html>
<?php



