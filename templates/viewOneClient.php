<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 06.09.2017
 * Time: 12:18
 */
require '../autoload.php';
if(isset($_GET['id'])){
//    если передали id значит работаем с ним иначе будем брать в else по умолчанию id=1
    $idClient = intval($_GET['id']);
}
else{
    $idClient = 1;
}
$client = \App\Models\Client::findObjByIdStatic($idClient);
//echo var_dump($client);
function viewNull($str){
    if(is_null($str))
        return "";
}
?>
    <!DOCTYPE HTML>
    <html lang="ru-RU">
    <title> просмотр/правка данных клиента </title>
    <?php     require_once('../head.html'); ?>
    <body>
    <div class="container">
        <div class="row">
            <?php require_once('header.html'); ?>
        </div>
        <div class="row"><!-- навигация -->
            <?php require_once('../navigation.html');?>
            <script>
                showLi('клиент');
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
                    <div class="col-lg-10   col-md-10 col-sm-10 col-xs-10   text-center ">просмотр / правка клиента <?php echo $client->name;?></div>
                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center"><button class="btn btn-sm btn-default" id="btnUpdateShow" >обновить</button></div>
                    <div class='col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center'><button class='btn btn-sm btn-primary' id='btnEnableUpdate' >править</button></div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <form action="../App/controllers/controllerOneClient.php" method="post">
                            <table>
                                <thead>
                                <tr>
                                    <td>название поля</td>
                                    <td>значение</td>
                                    <td class="tdDisplayNone">новое значение</td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="trDisplayNone"><td>id</td><td><?php echo $client->id ;?></td><td class="tdDisplayNone"><input  name="id" type="text" value="<?php echo $client->id ?>"/></td></tr>
                                <tr><td>название</td><td><?php echo $client->name ;?></td><td class="tdDisplayNone"><textarea name="name" type="text" cols="50" rows="2" maxlength="100" title ="<?php echo $client->name ?>" ><?php echo $client->name ?></textarea></td></tr>
                                <tr><td>контакт</td><td><?php echo $client->contactPerson ; ?></td><td class="tdDisplayNone"><input maxlength="100" size="55" name="contactPerson" title="<?php echo $client->contactPerson ?>" type="text" value="<?php echo $client->contactPerson ?>"/></td></tr>
                                <tr><td>телефон 1</td><td><?php echo $client->phone0 ; ?></td><td class="tdDisplayNone"><input maxlength="20" size="20" name="phone0" title="<?php echo $client->phone0 ?>" type="text" pattern="(\d{5,13})|(null)|()" value="<?php echo $client->phone0 ?>"/></td></tr>
                                <tr><td>телефон 2</td><td><?php echo $client->phone1 ; ?></td><td class="tdDisplayNone"><input maxlength="20" size="20" name="phone1" title="<?php echo $client->phone1 ?>" type="text" pattern="(\d{5,13})?|(null)? |()?" value="<?php echo viewNull( $client->phone1); ?>"/></td></tr>
                                <tr><td>email</td><td><a title="написать письмо клиенту" href="mailto:<?php echo $client->email0 ; ?>" ><?php echo $client->email0  ?></a></td><td class="tdDisplayNone"><input name="email0" maxlength="30" size="50" type="text" value="<?php echo $client->email0 ?>"/></td></tr>
                                <tr><td>адрес</td><td><?php echo $client->address ; ?></td><td class="tdDisplayNone"><textarea  name="address" maxlength="200" cols="50" rows="4" type="text" ><?php echo $client->address ?></textarea></td></tr>
                                <tr><td></td><td></td><td class="tdDisplayNone"><input name="btnSend" type="submit"/></td></tr>
                                <tr class="trDisplayNone"><td>скрытое поле</td><td>маяк</td><td><input name="submitUpdateClient" /></td></tr>
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
