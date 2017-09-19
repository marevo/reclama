<?php
//добавление заказа в базу
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 05.07.2017
 * Time: 16:30
 */
require '../autoload.php';
//функция вернет всех Client
function clientsOptions(){
    $option='';
    $clientsAll = \App\Models\Client::findAll();
    foreach ($clientsAll as $rowItem){
            $option .= "<option data-id = '$rowItem->id' value='$rowItem->id'>$rowItem->name</option>";
    }
    return $option;
}
//функция вставки в базу нового заказа
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
showLi('создать заказ')
        </script>

    </div>
    <div class="row">
        <div class="col-lg-2 backForDiv">
            этот див слева от таблицы в нем можно расположить дополнительные кнопки добавить редактировать удалить
        </div>
        <div class="col-lg-10 backForDiv">
            <!--строка показа времени и показа результатов ответа сервера  -->
            <?php include_once '../App/html/forDisplayTimeShowAnswerServer.html' ?>
            <div class="row">
                <div class="col-lg-12   col-md-12 col-sm-12 col-xs-12 bg-primary  h2 text-center text-info">создание и добавление в базу нового заказа</div>
            </div>
            <div class="row"><!--форма добавления нового заказа в базу -->
                <div class="col-lg-8 сol-md-8 col-sm 10 col-xs-12">
                        <form  id="formOneOrder"   method="post" action="../App/controllers/controllerAddNewOrderToBase.php">
                            <table>
                                <thead>
                                <tr class="trDisplayNone">
                                    <td>название поля</td>
                                    <td>значение поля</td></tr></thead>
                                <tbody>

                                <tr><td class="text-right"><label for="nameOrder">название заказа</label></td>
                                    <td class="text-left"><textarea type="text" name="nameOrder" cols="60" rows="2" maxlength="120" placeholder="введите название заказа"
                                                                    autofocus required></textarea></td>
                                </tr>
                                <tr><td class="text-right"><label for="descriptionOrder">описание заказа заказа</label></td>
                                    <td class="text-left"><textarea type="text" name="descriptionOrder" maxlength="3000"
                                                                    placeholder= "подробное описание заказа максимум 3000 символов"
                                                                    cols="100" rows="5"  required></textarea></td>
                                </tr>
                                <tr>
                                    <td><label for="InputValuerSearchClient" поиск клиента по имени</td>
                                    <td><input type="text" name="InputValuerSearchClient" class="fontSizeMedium" size="35" maxlength="35" value=""
                                               placeholder="поиск клиента не менен 3 символов" title="введите не менене 3 символов и нажмите кнопку искать"/>
                                        <button class="btn btn-primary" name="btnSearchClient"><span class="glyphicon glyphicon-search"> искать</span></button></td></tr>
                                <tr><td><label for="idClient">клиент</label></td>
                                    <td><select name="idClient"   class="fontSizeMedium"><option value="0">выберите клиента</option>
                                            <?php echo clientsOptions();  ?>
                                            <!--                                            <option data-id="1">чп Пупкин В C</option>-->
                                            <!--                                            <option data-id="2">фирма Рога и Копыта</option>-->
                                        </select></td></tr>

                                <tr><td> <label for="source">источник заказа</label></td>
                                    <td><input type="radio" name="source" value="0" checked/> не известен</br>
                                    <input type="radio" name="source" value="1"/> входящий звонок</br>
                                    <input type="radio" name="source" value="2"/> prom.ua</br>
                                    <input type="radio" name="source" value="3"/> olx</br>
                                    <input type="radio" name="source" value="4"/> сайте</br>
                                    <input type="radio" name="source" value="5"/> объявление в газете</br>
                                    <input type="radio" name="source" value="6"/> другой</td></tr>

                                <tr><td><label for="orderPrice">цена заказа</label></td>
                                    <td><input type="text" name="orderPrice" value="0.00" pattern="\d{1,5}(\.)?\d{1,2}" placeholder="0.00 или 1 или 1.0" title="формат целые или десятичные числа"/></td></tr>
                                <tr><td><label for="manufacturingPrice"> цена составляющих материалов</label></td>
                                    <td><input type="text" name="manufacturingPrice" pattern="\d{1,5}(\.)?\d{1,2}" value="0.00" title="формат целые или десятичные числа"/></td></tr>
                                <tr><td><label for="isCompleted">состояние заказа</label></td>
                                    <td><input type="radio" name="isCompleted" value="0" checked/> не укомплектован<br>
                                    <input type="radio" name="isCompleted" value="1"/> укомплектован</td></tr>
                               <tr>
                                   <td><label for="isReady">степень готовности</label></td>
                                   <td><input type="radio" name="isReady" value="0" checked/> новый<br>
<!--                                       <input type="radio" name="isReady" value="1"/>закрыт успешно<br>-->
<!--                                       <input type="radio" name="isReady" value="2"/>закрыт неуспешно<br>-->
<!--                                       <input type="radio" name="isReady" value="3"/>запущен в производство</td>-->
                               </tr>
                               <tr class="trDisplayNone">
                                   <td><label for="isInstall">установлен у клиента</label></td>
                                   <td><input type="radio" name="isInstall" value="0" checked/> не установлен<br>
<!--                                       <input type="radio" name="isInstall" value="1"/>в процессе установки<br>-->
<!--                                       <input type="radio" name="isInstall" value="2"/>установлен</td>-->
                               </tr>
                               <tr>
                                    <td><label for="dateOfOrdering">дата взятия заказа</label></td>
                                    <td><input type="date"  name="dateOfOrdering" required/></td></tr>
<script>
//установить максимальную дату сегодня
document.addEventListener('DOMContentLoaded', function() {
    $('input[name = "dateOfOrdering"]').val(getDate());
    $('input[name = "dateOfComplation"]').val(datePlusDays(14));
});
</script>
                                <tr>
                                    <td><label for="dateOfComplation">дата закрытия заказа</label></td>
                                    <td><input type="date" name="dateOfComplation"></td></tr>
                                <tr class="trDisplayNone">
                                    <td><label for="isAllowCalculateCost">разрешить менять цену комплектующих в заказе при изменении стоимости копмлектующих</label></td>
                                    <td><input type="radio" name="isAllowCalculateCost" value="0" /> не разрешать<br><input type="radio" name="isAllowCalculateCost" value="1" checked/>разрешать</td></tr>
                                <tr>
                                    <td></td>
                                    <td><div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" id="rezZaprosaKServer" >
                                            <div class="uspeh text-center "><span class="glyphicon glyphicon-import "> успешно</span></div>
                                            <div class="noUspeh text-center "><span class="glyphicon glyphicon-alert "> ошибка обратитесь к разработчику</span></div>
                                            <!-- в поле с классом divForAnswerServer будем получать ответы сервера (script ) -->
                                            <div class="divForAnswerServer"></div>
                                        </div></td></tr>
                                <tr><td><label for="submitFromFormOneOrder">после заполнения полей формы нажмите кнопку отправить</label> </td><td><input type="submit" name="submitFromFormOneOrder"/></td></tr>
                                <tr class="trDisplayNone"><td><label for="controlka"></label> контролька</td><td><input name="controlka" value="sendNewOrderToBase"/></td></tr>
                                </tbody>
                            </table>
                        </form>
                    <script type="text/javascript">
//                        при покидании поля ввода названия заказа проверим на наличие такого же названия и выведем предупреждение если есть такой
                        $('form [name="nameOrder"]').on('blur',function (event) {
                            //удалим предупреждение если оно было раньше
                            $('[class~=alertDelete]').remove();
                            var nameNewOrder = $.trim($(this).val());
                            if(nameNewOrder.length>2){
                                //тестируем имя на уникальность и сервер выведет предупреждение если такое имя заказа уже есть в базе
                                jquery_send('.divForAnswerServer','post',$('form').attr('action'),
                                    ['testNameUnuque','nameNewOrder'],['',nameNewOrder]);
                            }
                            else {
                                $(this).before('<div class="backgroundAlertRed alertDelete">минимальное количество символов 3</div>');
                            }
                        });

                        $('form').submit(function(){
                            $(this).find('.alert .alert-info').remove();
                            $('[class~=alertDelete]').remove();
//проверка данных отправляемых с формы
                            if($(this).find('select').val() == 0){
                                $(this).find('select').before('<div class="alert alert-info">выберитите клиента из выпадающего списка<br/>или найдите по имени затем выберите</div>');
                                return false;
                            }
//                               return;
//                            return false;
//                            $url = $(this).attr('action');
//                            $data=$(this).serializeArray();
//                            $.post(
//                                $(this).attr('action'),//ссылка куда идут данные
////                                $(this).serialize() ,   //Данные формы
//                                $(this).serializeArray()//сериализирует в виде массива
//                            );
////                            return false;
                            $.ajax({
                                type:'POST',
                                url:$(this).attr('action'),//куда идут данные
                                data:$(this).serializeArray(),//данные в виде массива метод serializeArray()
                                success:function (data) {
                                    $('.divForAnswerServer').html(data);
                                }
                                
                            });

                            $(this).find('.alert').remove();
                            return false;
                        });
                        //повесим клик на кнопку поиска
                        $('form [name="btnSearchClient"]').on('click',function () {
                            console.log('сработала кнопка поиска клиента');
                            var inValueNode = $('form [name = "InputValuerSearchClient"');
                            var inputValueSC = $.trim(inValueNode.val());
                            if(inputValueSC.length < 3 ){
                                $(inValueNode).val('');
                            }else {
                                //посылаем запрос на сервер для поиска всех клиентов по подобию
                                jquery_send('[name=idClient]','post','../App/controllers/controllerAddNewOrderToBase.php',
                                    ['searchClientLikeName','likeName'],
                                    ['',inputValueSC]
                                );
//                                jquery_send('.divForAnswerServer','post','../App/controllers/controllerAddNewOrderToBase.php',
//                                    ['searchClientLikeName','likeName'],
//                                    ['',inputValueSC]);
                            }
                            return false;
                        });
                    </script>
                </div>
            </div><!-- .row ->
        </div>
    </div>
</div>


</body>
</html>

