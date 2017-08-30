<?php
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
?>
<!DOCTYPE HTML>
<html>
<?php include ('../head.php')?>
<body>
<div class="container">
    <div class="row">
        <?php require_once('header.php'); ?>
    </div>
    <div class="row"><!-- навигация -->
        <?php include ('../navigation.php');?>
    </div>
    <div class="row">
        <div class="col-lg-2 backForDiv">
            этот див слева от таблицы в нем можно расположить дополнительные кнопки добавить редактировать удалить
        </div>
        <div class="col-lg-10 backForDiv">
            <div class="row"><!--форма добавки заказа-->
                <div class="col-lg-12 bg-primary  h3 text-center text-info">
                    добавление заказа
                </div>
                <!--                <div class="col-lg-3 ">-->
                <!--                    <button class="btn btn-primary"><span class="glyphicon glyphicon-edit">править</span></button>-->
                <!--                    <button class="btn btn-danger"><span class="glyphicon glyphicon-trash">удалить</span></button>-->
                <!--                </div>-->
            </div>
            <div class="row">
                <div class="col-lg-6">
                        <form  id="formOneOrder"   method="post"  action='../controllerOneOrder.php'>

                    <!--                         role="form"      action="../controllerOneOrder.php"-->
                            <table>
                                <thead><tr>
                                    <td>название поля</td>
                                    <td>значение поля</td></tr></thead>
                                <tbody>
<!--для проверки input pattern -->
<tr>
    <td>кнопка для проверки отправки</td>
    <td><input type="submit" name="inputFromFormOneOrder"/>

<!--               value="просто проверка"-->
        </td></tr>
<tr>
   <!--для проверки input pattern -->

                                <tr><td class="text-right"><label for="nameOrder">название заказа</label></td>
                                    <td class="text-left"><textarea type="text" name="nameOrder"  placeholder="введите название заказа" style="min-height: 100px;" autofocus required></textarea></td></tr>
                                <tr><td><label for="idClient">клиент</label></td>
                                    <td><select name="idClient"  required ><option value="0">выберите клиента</option>
                                    <?php echo clientsOptions();  ?>
            <!--                                            <option data-id="1">чп Пупкин В C</option>-->
            <!--                                            <option data-id="2">фирма Рога и Копыта</option>-->
                                    </select></td></tr>
                                <tr><td> <label for="source">источник заказа</label></td>
                                    <td><input type="radio" name="source" value="0" checked/>не известен<br>
                                    <input type="radio" name="source" value="1"/>входящий звонок<br>
                                    <input type="radio" name="source" value="2"/>prom.ua<br>
                                    <input type="radio" name="source" value="3"/>olx<br>
                                    <input type="radio" name="source" value="4"/>сайте<br>
                                    <input type="radio" name="source" value="5"/>объявление в газете</td></tr>

                                <tr><td><label for="orderPrice">цена заказа</label></td>
                                    <td><input type="number" name="orderPrice" value="00.00" pattern="\d{1,7}(\.|,)\d{2}" placeholder="введите цену заказа"/></td></tr>
                                <tr><td><label for="manufacturingPrice"> цена составляющих материалов</label></td>
                                    <td><input type="number" name="manufacturingPrice" pattern="\d{1,7}(\.|,)\d{2}" value="00.00"/></td></tr>
                                <tr><td><label for="isCompleted">состояние заказа</label></td>
                                    <td><input type="radio" name="isComleted" value="0" checked/>не укомплектован<br>
                                    <input type="radio" name="isComleted" value="1"/>укомплектован</td></tr>
                               <tr>
                                   <td><label for="isReady">степень готовности</label></td>
                                   <td><input type="radio" name="isReady" value="0" checked/>новый<br><input type="radio" name="isReady" value="1"/>закрыт успешно<br><input type="radio" name="isReady" value="2"/>закрыт неуспешно</td></tr>
                               <tr>
                               <tr>
                                   <td><label for="isInstall">установлен у клиента</label></td>
                                   <td><input type="radio" name="isInstall" value="0" checked/>не установлен<br><input type="radio" name="isInstall" value="1"/>в процессе установки<br><input type="radio" name="isInstall" value="2"/>установлен</td></tr>
                               <tr>
                                   <td><label for="isDeposite">предоплата</label></td>
                                   <td><input type="number" name="isDeposite" placeholder="грн.коп"/></td></tr>
                                <tr>
                                    <td><label for="isFullPaid">полная оплата</label></td>
                                    <td><input type="number" name="isFullPaid" placeholder="грн.коп"/></td></tr>
                                <tr>
                                    <td><label for="dateOfOrdering">дата взятия заказа</label></td>
                                    <td><input type="date" max="" name="dateOfOrdering" required/></td></tr>
<script>
//установить максимальную дату сегодня
document.addEventListener('DOMContentLoaded', function() {
    $('input[name = "dateOfOrdering"]').val(getDate());
});
</script>
                                <tr>
                                    <td><label for="dateOfComplation">дата закрытия заказа</label></td>
                                    <td><input type="date" name="dateOfComplation"></td></tr>
                                <tr>
                                    <td><label for="isAllowCalculateCost">разрешить менять цену комплектующих при изменении стоимости</label></td>
                                    <td><input type="radio" name="isAllowCalculateCost" value="0" checked/> не разрешать<br><input type="radio" name="isAllowCalculateCost" value="1"/>разрешать</td></tr>
                                </tbody>
                            </table>


                        </form>
                    <script>
                        $('form').submit(function(){
                            $(this).find('.alert .alert-info').remove();
//проверка данных отправляемых с формы
                            if($(this).find('select').val() == 0){
                                $(this).find('select').before('<div class="alert alert-info">выбертите клиента из выпадающего списка</div>');
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
<!--                <div class="col-lg-6">-->
<!--                    <table id="tableOneOrder"><thead><tr><td>данные заказа</td>-->
<!--                            <td>значение</td>-->
<!--                            <td><span class="glyphicon glyphicon-edit"></span></td>-->
<!--                            <td><span class="glyphicon glyphicon-plus-sign"></span></td>-->
<!--                            <td><span class="glyphicon glyphicon-trash"></span></td></thead>-->
<!--                        <tbody>-->
<!--                        <tr><td>название</td><td>--><?php // echo $order->nameOrder;?><!--</td></tr>-->
<!--                          0-не известен, 1-входящий звонок, 2-prom.ua, 3-olx, 4-сайт, 5 реклама в газете -->
<!--                        <tr><td>источник</td><td>--><?php // echo fSource($order->source); ?><!--</td></tr>-->
<!--                        <tr><td>название клиента</td><td>--><?php // echo  $nameClient;?><!--</td></tr>-->
<!--                        <tr><td>цена</td><td>--><?php // echo $order->orderPrice;?><!--</td></tr>-->
<!--                        <tr><td>цена комплектующих</td><td>--><?php // echo $order->manufacturingPrice;?><!--</td></tr>-->
<!--                        <tr><td>комплектация</td><td>--><?php // echo fIsCompleet($order);?><!--</td></tr>-->
<!--                        --><?php ////функция вернет строку сразу статуса заказа
//                        echo fIsReady($order->isReady); ?>
<!--                        --><?php ////верноет строку таблицы отображение установлен или нет заказ у клиента(повесили ли вывеску, прикрепили ли баннер)
//                        echo fIsInstall($order->isInstall);?>
<!--                        <tr><td title="если внесена предоплата">предоплата</td><td>--><?php //echo $order->isDeposite; ?><!--</td></tr>-->
<!--                        <tr><td title=" обычно равна цене или 'цена'-'предоплата'">полная оплата</td><td>--><?php //echo $order->isFullPaid; ?><!--</td></tr>-->
<!--                        <tr><td title='дата заключения сделки на производство'>дата заключения сделки</td><td> <data>--><?php //echo $order->dateOfOrdering; ?><!--</data></td></tr>-->
<!--                        <tr><td title=' дата установки у заказчика ли дата закрытия заказа если отказались по какой либо причине'>дата завершения сделки</td><td> <data >--><?php //echo $order->dateOfComlation; ?><!--</data></td></tr>-->
<!--                        <tr><td title='разрешение на изменение цены комплектующих (если они изменялись )'>разрешить пересчет цены комплекующих</td><td>--><?php //if($order->isAllowCalculateCost==0) echo 'нельзя';else echo 'можно';  ?><!--</td></tr>-->
<!--                        </tbody>-->
<!--                    </table>-->
<!--                </div>-->
            </div>
        </div>
    </div>
</div>


</body>
</html>

