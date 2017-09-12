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
<html lang="ru-RU">
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
            <!--  блок отображения что меняем и кнокпки обновить страницу и кнопка править(покажет поля для внесения новых значений)  -->
            <div class="row headingContent">
                <div class="col-lg-10   col-md-10 col-sm-10 col-xs-10   text-center ">правка поставщика <?php echo $supp->name;?></div>
                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center"><button class="btn btn-sm btn-default" id="btnUpdateShow" >обновить</button></div>
                <!--   если не использовоались материалы этого поставщика, то можно разрешить его редактирование -->
                <?php
                $isExistMaterialsThisSupplierInAnyOrder = \App\Models\MaterialsToOrder::ifExistThisSupplierInAnyMaterilsToOrder($idSupplier);
                if(! $isExistMaterialsThisSupplierInAnyOrder){
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
                    <form action="../App/controllers/controllerOneSupplier.php" method="post">
                        <table>
                            <thead>
                            <tr>
                                <td>название поля</td>
                                <td>значение</td>
                                <td class="tdDisplayNone">новое значение</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr style="display: none;"><td>id</td><td><?php echo $supp->id ;?></td><td class="tdDisplayNone"><input  name="id" type="text" value="<?php echo $supp->id ?>"/></td></tr>
                            <tr><td>название</td><td><?php echo $supp->name ;?></td><td class="tdDisplayNone"><input name="name" type="text" size="55" maxlength="200" title ="<?php echo $supp->name ?>" value="<?php echo $supp->name ?>"/></td></tr>
                            <tr><td>дополнительные сведения</td><td><?php echo $supp->addCharacteristic ; ?></td><td class="tdDisplayNone"><textarea maxlength="300" cols="60" rows="5" name="addCharacteristic" title="<?php echo $supp->addCharacteristic ?>" type="text" ><?php echo $supp->addCharacteristic ?></textarea></td></tr>
                            <tr><td>контакт</td><td><?php echo $supp->contactPerson ; ?></td><td class="tdDisplayNone"><input maxlength="100" size="55" name="contactPerson" title="<?php echo $supp->contactPerson ?>" type="text" value="<?php echo $supp->contactPerson ?>"/></td></tr>
                            <tr><td>телефон</td><td><?php echo $supp->phone0 ; ?></td><td class="tdDisplayNone"><input maxlength="20" size="20" name="phone0" title="<?php echo $supp->phone0 ?>" type="text" value="<?php echo $supp->phone0 ?>"/></td></tr>
                            <tr><td>email</td><td><a title="написать письмо поставщику" href="mailto:<?php echo $supp->email0 ; ?>" ><?php echo $supp->email0  ?></a></td><td class="tdDisplayNone"><input name="email0" maxlength="50" size="50" type="text" value="<?php echo $supp->email0 ?>"/></td></tr>
                            <tr><td>адрес</td><td><?php echo $supp->address ; ?></td><td class="tdDisplayNone"><input  name="address" maxlength="200" size="55" type="text" value="<?php echo $supp->address ?>"/></td></tr>
                            <tr><td>день доставки</td><td><?php echo $supp->getDeliveryDays(); ?></td><td class="tdDisplayNone"><select name="deliveryDay" >
                                        <option value="1">понедельник</option>
                                        <option value="2">вторник</option>
                                        <option value="3">среда</option>
                                        <option value="4">четверг</option>
                                        <option value="5">пятница</option>
                                        <option value="6">все рабочие дни</option>
                                        <option value="8">не установлено</option>
                                </td></tr>
                            <tr><td>сайт поставщика</td><td><a target="_blank" title="перейти на сайт поставщика" href="<?php echo $supp->site  ?>"><?php echo $supp->site  ?></a></td><td class="tdDisplayNone"><input name="site" type="text" size='55' value = "<?php echo $supp->site  ?>"/></td></tr>
                            <tr><td></td><td></td><td class="tdDisplayNone"><input name="btnSend" type="submit"/></td></tr>
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

    $(function () {
        //повесим обработчик кнопки  будет осуществлена перезагрузка страницы
        $('#btnUpdateShow').on('click',function () {
            location.reload();
        }) ;
        //по загрузке всех данных выберет в selecte для update поле которое сейчас есть в базе
        $('select').val('<?php echo $supp->deliveryDay ;?>');
        //повесим обработчик кнопки btnEnableUpdate он будет открывать для update изменение поля поставщика
        $('#btnEnableUpdate').on('click',function () {
            $('.tdDisplayNone').each(function () {
                $(this).css('display',function (i,value) {
                   if(value == 'block')
                       return 'none';
                   else return 'block';
                });
            });
        });
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
