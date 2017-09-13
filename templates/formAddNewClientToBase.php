<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 05.09.2017
 * Time: 20:16
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
            showLi('добавить клиента')
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
                <div class="col-lg-12   col-md-12 col-sm-12 col-xs-12 bg-primary  h2 text-center text-info">добавление клиента в базу</div>

            </div>
            <div class="row"><!--форма добавки клиента в базу -->
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pading0">
                    <form  id="formOneMaterial"   method="post" action="../App/controllers/controllerAddNewClientToBase.php" >
                        <table>
                            <thead><tr class="trDisplayNone">
                                <td>название поля</td>
                                <td>значение поля</td></tr></thead>
                            <tbody>

                            <tr class="trDisplayNone">
                                <td class="text-right"><label for="sendClientToAddToBase">скрытое поле  для отправки маркера</label></td>
                                <td class="text-left"><input  name="sendClientToAddToBase"  value="sendMarker"  /></td>
                            </tr>
                            <tr><td class="text-right"><label for="nameClient">название клиента</label></td>
                                <td class="text-left"><textarea cols="50" rows="2" name="nameClient" id="idNameClient" placeholder="введите название клиента max 100 символов" required ></textarea></td>
                            </tr>
                            <tr><td class="text-right"><label for="contactPerson">контактное лицо фио</label></td>
                                <td class="text-left"><input maxlength="100" size="55"  name="contactPerson" placeholder="Иванов Иван Иванович"  /></td>
                            </tr>
                            <tr><td class="text-right"><label for="phone0">телефон 1</label></td>
                                <td><input type="tel" name="phone0" placeholder="телефон0"  pattern="\d{5,10}" title="формат телефона от 5 до 10 цифр !"/></td></tr>
                            <tr><td class="text-right"> <label for="phone1">телефон 2</label></td>
                                <td><input type="tel" name="phone1" placeholder="телефон1" pattern="\d{5,10}" title="формат телефона от 5 до 10 цифр !" /> </td></tr>
                            <tr><td class="text-right"><label for="email0">email</label></td>
                                <td><input type="email" name="email0"   placeholder="vova@vova.ua" title="формат email vova@vova.ua или vova@vova.ua.com"/></td>
                            </tr>
                            <tr><td class="text-right"><label for="address">адрес клиента</label></td>
                                <td class="text-left"><textarea cols="100" rows="2" name="address" title="занесите хотя бы название города/села" placeholder="адресс максимум 200 символов" required></textarea></td>
                            </tr>

                            <tr><td class="text-right"></td><td><input type="submit"  name="submitFromFormOneClient"/></td>
                            </tr>

                            </tbody>
                        </table>
                    </form>
                    <script type="text/javascript">
//                        $('form select').on('change',function () {
//                            if($(this).val() == 0) {
//                                $('.alert .alert-info').remove();
//                                $(this).before('<div class="alert alert-info">выберитите поставщика из выпадающего списка</div>');
//                                return false;
//                            }
//                            else
//                                $('.alert').remove();
//                            return false;
//                        });
                        //обработка полей textarea  на макс кол символов и отсутсвие пробелов по краям
                        $('form textarea').on('blur',function () {
                            $(this).parent('.alert').remove();
                            $(this).val($.trim($(this).val()));
                            console.log('убрали пробелы');
                            if(this.name == 'nameClient'){
                                    if($(this).val().length > 100 ) {
                                        var el = $(this);
                                        elem.value = elem.value.substr(0, 100);
                                        console.log('обрезали длину названия клиента до 100 символов');
                                    }
                                //  проверка на уникальность имен клиента - не может быть 2 одинаковых name клиента
                                if($(this).val().length > 0)
                                    jquery_send('.divForAnswerServer','post',
                                        '../App/controllers/controllerAddNewClientToBase.php',
                                        ['testOnUniqNameClient','nameClient'],['',$(this).val()]);
                                $(this).parent().find('.alert').remove();
                            }
                            if((this).name == 'adderss'){
                                if($(this).val().length > 200 ) {
                                    var el = $(this);
                                    elem.value = elem.value.substr(0, 200);
                                    console.log('обрезали длину адрес клиента до 200 символов');
                                    $(this).parent().find('.alert').remove();
                                }
                            }
                            return false;
                        });
                        //обработка полей телефона и имейла
                        $('form input').on('blur',function (event) {
//                            удалили старые предупреждения о не правильном формате номера телефона
//                            $('[class~=alertDelete]').remove();
                            if((this.name == 'phone0' || this.name == 'phone1')&& $(this).val().length>0 ){
                               console.log('заносили данные в поля телефона phone0 или phone1');
                               var inputPhoneValue = $(this).val();
//                               eсли не прошли тест на правильность - вставим предупреждение
                               if(testOnPhone(inputPhoneValue) == false){
                                   $(this).parent().find('[class~=alertDelete]').remove();
                                   $(this).before('<div class="alertDelete backgroundAlertRed">формат номера от 5 до 10 цифр</div>');
                               }else {
//                                   $(this).prev().remove();
                                   $(this).parent().find('[class~=alertDelete]').remove();
                               }
                           }
                           if(this.name == 'email0' && $(this).val().length>0){
                               console.log('заносили данные в полe email0');
                               var inputEmailValue = $(this).val();
                               if(testOnEmail(inputEmailValue) == false){
                                   $(this).parent().find('[class~=alertDelete]').remove();
                                   $(this).before('<div class="alertDelete backgroundAlertRed">не правильный формат email - исправьте</div>');
                               }else {
//                                 $(this).prev().remove();
                                   $(this).parent().find('[class~=alertDelete]').remove();

                               }

                           }
                        });
//обязательные поля для заполнения название клиента, телефон, адрес
                        $('form').submit(function () {
                            if ($(this).find('[name=nameClient]').val() == "" || $(this).find('[name=nameClient]').prev().hasClass('alert alert-info') ) {
                                $(this).find('[name=nameClient]').prev().remove();
                                $(this).find('[name=nameClient]').before('<div class="alert alert-info">имя клиентa обязательно только уникальное!!</div>');
                                return false;
                            }
                            if($(this).find('[name=address]').val() ==""){
                                $(this).find('[name=address]').prev().remove();
                                $(this).find('[name=address]').before('<div class="alert alert-info">надо заполнить адрес (хотябы название города/села)</div>');
                                return false;
                            }
//                            $.post(
//                                $(this).attr('action'),//ссылка куда идут данные
////                                $(this).serialize() ,   //Данные формы
//                                $(this).serializeArray(),//сериализирует в виде массива
//                            );
                            //не пустим пока на сервер для добавления нового клиента
//                            console.log('отправки на добавку нового клиента в базу нет - удалите return false на строку ниже ');
//                            return false;
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
