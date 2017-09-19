<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 29.08.2017
 * Time: 17:51
 */

require '../autoload.php';
//функция подгрузки из базы поставщиков по имени

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
            showLi('добавить поставщика в базу')
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
                <div class="col-lg-12   col-md-12 col-sm-12 col-xs-12 bg-primary  h2 text-center text-info">добавление поставщика в базу</div>

            </div>
            <div class="row"><!--форма добавки материала -->
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pading0 ">
                    <form  id="formOneSupplier"   method="post" action="../App/controllers/controllerOneSupplier.php" >
                        <table>
                            <thead><tr class="trDisplayNone">
                                <td>название поля</td>
                                <td>значение поля</td></tr></thead>
                            <tbody>
                            <!-- скрытое поле для отправки маркера-->
                            <tr style="display: none;">
                                <td class="text-right"><label for="addNewSupplier">скрытое поле  для отправки маркера</label></td>
                                <td class="text-left"><input name="addNewSupplier" /></td>
                            </tr>
                            <tr><td class="text-right"><label for="nameSupplier">название поставщика</label></td>
                                <td><textarea name="nameSupplier"  cols="50" rows="4" placeholder="название поставщика 200 символов" required ></textarea></td>
                            </tr>
                            <tr><td class="text-right"><label for="addCharacteristic">дополнительные характеристики</label></td>
                                <td class="text-left"><textarea cols="50" rows="6"  name="addCharacteristic" placeholder="что-то типа поставка пленки... " required ></textarea></td>
                            </tr>

                            <tr><td class="text-right"><label for="contactPerson">контактное лицо</label></td>
                                <td class="text-left"><input size="50" name="contactPerson" placeholder="ФИО контакта максимально 100 символов" required maxlength="100" /></td>
                            </tr>
                            <tr><td class="text-right"><label for="phone0">телефон</label></td>
                                <!--                                <input type='tel' pattern='([\+]\d{2})?\d{3}[\-]\d{4}[\-]\d{3}' title='Phone Number (Format: +38 093-799-7990)'>-->
<!--                                <td><input type='tel' name="phone0" pattern='(([\+])?\d{2}?)?\d{3}[\-]\d{4}[\-]\d{3}' title='Phone Number (Format: +380937997990 or 0937997990)'></td></tr>-->
                                <td><input type='tel' name="phone0" pattern='\d{5,10}' title='Format:от 5 до10 цифр 0937997990 or 52525)'></td></tr>
                            <!---->
                            <tr><td class="text-right"> <label for="email0">email</label></td>
                                <td><input name="email0" maxlength="50" size="50" type="email"/> </td></tr>

                            <tr><td class="text-right"><label for="address">адрес поставщика</label></td>
                                <td><input name="address"  size="75" placeholder="адрес поставщика 200 символов" maxlength="200" required /></td></tr>
                            <tr><td class="text-right"><label for="deliveryDay">день доставки</label> </td>
                                <td><select name="deliveryDay">
                                        <option value="0">выберите день</option>
                                        <option value="1">понедельник</option>
                                        <option value="2">вторник</option>
                                        <option value="3">среда</option>
                                        <option value="4">четверг</option>
                                        <option value="5">пятница</option>
                                        <option value="6">все рабочие дни</option>
                                        <option value="8">не установлено</option>
                                    </select>
                                </td></tr>

                            <tr><td class="text-right"><label for="site">сайт</label></td>
                                <td><input  name="site" maxlength="300" size="55" type="url"/></td>
                            </tr>
                            <tr><td class="text-right"></td><td><input type="submit"  name="submitFromFormOneSupplier"/></td>
                            </tr>

                            </tbody>
                        </table>
                    </form>
                    <script>
                        $('form select').on('change',function () {
                            if($(this).val() == 0) {
                                $('.alert .alert-info').remove();
                                $(this).before('<div class="alert alert-info">выберитите день поставки в ваш город</div>');
                                return false;
                            }
                            else
                                $('.alert').remove();
                            return false;
                        });
                        $('form textarea').on('blur',function () {
                            if( $(this).attr('name') == 'nameSupplier'){
                                $(this).val($.trim($(this).val()));
                                if($(this).val().length > 200){
                                   var el = $(this);
                                    elem.value = elem.value.substr(0, 200);
                                    console.log('обрезали длину названия до 200 символов');

                                }
                                if($(this).val() != ''){
                                    $('.alert').remove();
                                    console.log('отправляем на сервер запрос есть ли такое имя в базе поставщиков имя должно быть уникально');
                                    jquery_send('.divForAnswerServer','post',
                                        '../App/controllers/controllerOneSupplier.php',
                                        ['isExistNameSupplier','nameSupplier'],
                                        ['',$(this).val()]);
                                }
                            }
                            if($(this).attr('name') == 'addCharacteristic'){
                                $(this).val($.trim($(this).val()));
                                if($(this).val().length > 300){
                                    var el = $(this);
                                    elem.value = elem.value.substr(0, 300);
                                    console.log('обрезали длину дополнительных характеристик до 300 символов');
                                }
                                if($(this).val() != ''){
                                    $('.alert').remove();
                                }
                            }
                            return false;
                        });
                        $('form input').on('blur',function () {
                            if($(this).attr('name') == 'phone0'){
                                //проведем тест на ввод телефона
                                var phone0 = $(this).val();
                                    //уберем ранее выведенный перед этим предупреждающий див
                                $(this).parent().find('.alert').remove();
                                if( testOnPhone(phone0)){
                                    //тест на правильность ввода пройден верно
                                    console.log('номер телефона введен по формату');
                                    console.log('отправляем на сервер запрос есть ли такой телефон в базе поставщиков - он должен быть уникальным');
                                    jquery_send('.divForAnswerServer','post',
                                        '../App/controllers/controllerOneSupplier.php',
                                        ['isExistPhone0Supplier','phone0Supplier'],
                                        ['',$(this).val()]);
                                }
                                else{
                                    $(this).before('<div class="alert alert-info"> введите телефон по формату '+ $(this).attr('title')+'</div>');
//                                    $(this).focus();
                                    return false;
                                }
                            }
                            if($(this).attr('name')=='email0'){
                                var email0 = $(this).val();
                                //уберем ранее выведенный перед этим предупреждающий див
                                $(this).parent().find('.alert').remove();
                                if(testOnEmail(email0)){
                                    console.log('email0 введен по формату');
                                    console.log('не будем отправлять на сервер запрос есть ли такой email в базе поставщиков - он должен быть уникальным или NULL по умолчанию');
                                }
                                else{
                                    $(this).before('<div class="alert alert-info"> не правильный формат email </div>');
//                                    $(this).focus();
                                    return false;
                                }                            }
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
