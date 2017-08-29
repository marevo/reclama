<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 12.08.2017
 * Time: 17:41
 */
require '../autoload.php';
if(isset($_GET['id'])){
//    если передали id значит работаем с ним иначе будем брать в else по умолчанию id=1
    $idSupplier = intval($_GET['id']);
}
else{
    $idSupplier = 1;
}
$supp = \App\Models\Supplier::findObjByIdStatic($idSupplier);
//echo var_dump($supp);
?>
<!DOCTYPE HTML>
<html>
<title> просмотр/правка данных поставщика </title>
<?php     require_once('../head.html'); ?>
<body>
<div class="container">
    <div class="row">
        <?php require_once('header.html'); ?>
    </div>
    <div class="row"><!-- навигация -->
        <?php require_once('../navigation.html');?>
        <script>
            showLi('поставщик');
        </script>
    </div>            <!-- конец навигации -->

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
                <div class="col-lg-10   col-md-10 col-sm-10 col-xs-10   text-center ">правка поставщика <?php echo $supp->name;?></div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center"><button id="btnUpdateShow" > обновить </button></div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <form action="../App/controllers/controllerOneSupplier.php" method="post">
                        <table>
                            <thead>
                            <tr>
                                <td>название поля</td>
                                <td>значение</td>
                                <td>новое значение</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr style="display: none;"><td>id</td><td><?php echo $supp->id ;?></td><td id="idSupplier" class="formMaterial"><input  name="id" type="text" value="<?php echo $supp->id ?>"/></td></tr>
                            <tr><td>название</td><td><?php echo $supp->name ;?></td><td class="formMaterial"><input name="name" type="text" size="55" maxlength="200" title ="<?php echo $supp->name ?>" value="<?php echo $supp->name ?>"/></td></tr>
                            <tr><td>дополнительные сведения</td><td><?php echo $supp->addCharacteristic ; ?></td><td class="formMaterial"><input maxlength="300" size="55" name="addCharacteristic" title="<?php echo $supp->addCharacteristic ?>" type="text" value="<?php echo $supp->addCharacteristic ?>"/></td></tr>
                            <tr><td>контакт</td><td><?php echo $supp->contactPerson ; ?></td><td class="formMaterial"><input maxlength="100" size="55" name="contactPerson" title="<?php echo $supp->contactPerson ?>" type="text" value="<?php echo $supp->contactPerson ?>"/></td></tr>
                            <tr><td>телефон</td><td><?php echo $supp->phone0 ; ?></td><td class="formMaterial"><input maxlength="20" size="20" name="phone0" title="<?php echo $supp->phone0 ?>" type="text" value="<?php echo $supp->phone0 ?>"/></td></tr>
                            <tr><td>email</td><td><?php echo $supp->email0 ; ?></td><td class="formMaterial"><input name="email0" maxlength="50" size="50" type="text" value="<?php echo $supp->email0 ?>"/></td></tr>
                            <tr><td>адрес</td><td><?php echo $supp->address ; ?></td><td class="formMaterial"><input  name="address" maxlength="200" size="55" type="text" value="<?php echo $supp->address ?>"/></td></tr>
                            <tr><td>день доставки</td><td><?php echo $supp->getDeliveryDays(); ?></td><td class="formMaterial"><select name="deliveryDay" >
                                        <option value="1">понедельник</option>
                                        <option value="2">вторник</option>
                                        <option value="3">среда</option>
                                        <option value="4">четверг</option>
                                        <option value="5">пятница</option>
                                        <option value="6">все рабочие дни</option>
                                        <option value="0">не установлено</option>
                                </td></tr>
                            <tr><td>сайт поставщика</td><td><?php echo $supp->site  ?></td><td class="formMaterial"><input name="site" type="text" size='55' value = "<?php echo $supp->site  ?>"/></td></tr>
                            <tr><td></td><td></td><td class="formMaterial"><input name="btnSend" type="submit"/></td></tr>
                            <tr style="display: none;"><td>скрытое поле</td><td>маяк</td><td><input name="submitOneSupplier" /></td></tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
    //функция показа полей для редактирования сейчас используется для перезагрузки страницы после отправки новых значений по submit
    function displayFormMaterial() {
        location.reload();
        return false;
        var i =0, j= 0;
        $('.formMaterial').each(function () {
            if($(this).css('visibility')=='visible'){
                $(this).css('visibility','hidden');
            }
            else {
                $(this).css('visibility','visible');
                //заполним input
                $(this).find('input').val( $(this).prev().textContent ) ;
            }
        });
        return false;
    }
    //по загрузке всех данных выберет в selecte для update поле которое сейчас есть в базе
    $(function () {
        $('#btnUpdateShow').on('click',displayFormMaterial) ;
        $('select').val('<?php echo $supp->deliveryDay ;?>')
    });
    //отправка по ajax запроса на update данных поставщика
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
