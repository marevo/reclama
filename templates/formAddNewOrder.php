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
function insertNewOrder()
{
    if (isset($_POST['submitFromFormOneOrder'])) {
        echo 'пришел запрос на добавление заказа <br/>';
        $orNew = new \App\Models\Order();
        $orNew -> isAllowCalculateCost = 0;
        $orNew -> isTrash = 0;
        if (isset($_POST['nameOrder'])) {
            $nameOrder = trim( htmlspecialchars($_POST['nameOrder']) );
            if (\App\Models\Order::isAllowNameOrder($nameOrder) == false) {
                $orNew->name = $nameOrder;
                echo 'заказа с именем '.$orNew->name.' нет, а значит  сможем добавить заказ </br>';
            } else echo "
 <script>
     $('#rezShow').text('есть такое имя заказа поменяйте на другое иначе вы не сможете создать заказ</br> ' +
      'нельзя создавать заказы с одинаковыми названиями');                           
</script>
                            ";
        }
        if (isset($_POST['descriptionOrder'])) {
            $descriptionOrder =trim( htmlspecialchars($_POST['descriptionOrder']));
            $orNew->descriptionOrder = $descriptionOrder;
        }
        if (isset($_POST['idClient'])) {
            $idClient = intval($_POST['idClient']);
            $orNew->idClient = $idClient;
        }
        if (isset($_POST['source'])) {
            $source = intval($_POST['source']);
            $orNew->source = $source;
        }
        if (isset($_POST['orderPrice'])) {
            $orderPrice = htmlspecialchars($_POST['orderPrice']);
            $orNew-> orderPrice = $orderPrice;
        }
        if (isset($_POST['manufacturingPrice'])) {
            $manufacturingPrice = htmlspecialchars($_POST['manufacturingPrice']);
            $orNew-> manufacturingPrice = $manufacturingPrice;
        }
        if (isset($_POST['isCompleted'])) {
            $isCompleted = intval($_POST['isCompleted']);
            $orNew -> isCompleted = $isCompleted;
        }
        if (isset($_POST['isReady'])) {
            $isReady = intval($_POST['isReady']);
            $orNew -> isReady = $isReady;
        }
        if (isset($_POST['isInstall'])) {
            $isInstall = intval($_POST['isInstall']);
            $orNew -> isInstall = $isInstall;
        }
        if (isset($_POST['isDeposite'])) {
            //пока не создан заказ мы не можем вносить оплаты по нему
            // поэтому просто сохраним пока в переменной, чтобы потом добавить оплату после создания заказа
            $isDeposite =  htmlspecialchars($_POST['isDeposite']);
        }
        else{
            $isDeposite = 0;
        }
        if(isset($_POST['dateOfOrdering'])){
            $dateOfOrdering = htmlspecialchars($_POST['dateOfOrdering']);
            $orNew -> dateOfOrdering = $dateOfOrdering;
        }
        if(isset($_POST['dateOfComplation'])){
            $dateOfComplation  = htmlspecialchars($_POST['dateOfComplation']);
            $orNew -> dateOfComplation = $dateOfComplation;
        }
        //вставим новый заказ в базу
        $resInsert = $orNew -> insert();
        if($resInsert != false){
            echo '<br/>USPESHNO добавлен заказ <br/>';
            $paymentFirst = new \App\Models\Payment();
            $paymentFirst -> date = $dateOfOrdering;
            $paymentFirst -> idClient = $orNew -> idClient;
            $paymentFirst -> sumPayment = $isDeposite;
            //получим объект класса Order
            $myOrder = \App\Models\Order::isAllowNameOrder($nameOrder);
            if($myOrder != false){
                //удалось создать заказ и вставить его в базу
                $myOrderId = $myOrder->id;
                $paymentFirst -> idOrder = $myOrderId;
                $resInsertPay = $paymentFirst ->insert();
                if($resInsertPay != false ){
                    $allPayments = \App\Models\Payment::showSumAllPayments($myOrderId);
                    echo "<br/>добавили оплату по заказу $nameOrder сумма всех оплат = $allPayments";
                }
                else echo "обратитесь к разработчику!!! ошибка добавления проверочной суммы при создании заказа( вы не сможете найти его в заказах)";
            }
            else echo "!!! ошибка. обратитесь к разработчику!!! заказ был добавлен успешно, но не нашли такого заказа";
        }
        else{
            echo"!!!ошибка в добавлении заказа не удалось добавить заказ обратитесь к разаработчику";
        }

        
//        if (isset($_POST['submitFromFormOneOrder']))
//            foreach ($orNew as $k => $value) {
//                echo "<br/>$k--- $value";
//            }
    }
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
            <div class="row"><!--форма добавки заказа-->
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 bg-primary  h2 text-center text-info">добавление заказа</div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-center " id="rezShow">результат добавления </div>
                    <?php
//обработка добавления заказа
                    insertNewOrder();
                    ?>
            </div>
            <div class="row">
                <div class="col-lg-6">
                        <form  id="formOneOrder"   method="post" >
                            <table>
                                <thead><tr>
                                    <td>название поля</td>
                                    <td>значение поля</td></tr></thead>
                                <tbody>
<!--для проверки input pattern -->
<tr><td>кнопка для проверки отправки</td><td><input type="submit" name="submitFromFormOneOrder"/>

<!--               value="просто проверка"-->
        </td></tr>
<tr><td><label for="idClient">клиент</label></td>
    <td><select name="idClient"  required ><option value="0">выберите клиента</option>
            <?php echo clientsOptions();  ?>
            <!--                                            <option data-id="1">чп Пупкин В C</option>-->
            <!--                                            <option data-id="2">фирма Рога и Копыта</option>-->
        </select></td></tr>

<tr><td class="text-right"><label for="nameOrder">название заказа</label></td>
                                    <td class="text-left"><textarea type="text" name="nameOrder" 
                                                                    placeholder="введите название заказа"
                                                                    style="min-height: 50px;" autofocus required>
                                        </textarea></td></tr>
                                <tr><td class="text-right"><label for="descriptionOrder">описание заказа заказа</label></td>
                                    <td class="text-left"><textarea type="text" name="descriptionOrder" 
                                                                    placeholder="введите название заказа"
                                                                    style="min-height: 100px;"  required>
                                        </textarea></td></tr>
                                <tr><td> <label for="source">источник заказа</label></td>
                                    <td><input type="radio" name="source" value="0" checked/>не известен</br>
                                    <input type="radio" name="source" value="1"/>входящий звонок</br>
                                    <input type="radio" name="source" value="2"/>prom.ua</br>
                                    <input type="radio" name="source" value="3"/>olx</br>
                                    <input type="radio" name="source" value="4"/>сайте</br>
                                    <input type="radio" name="source" value="5"/>объявление в газете</br>
                                    <input type="radio" name="source" value="6"/>другой</td></tr>

                                <tr><td><label for="orderPrice">цена заказа</label></td>
                                    <td><input type="text" name="orderPrice" value="00.00" pattern="\d{1,7}(\.|,)\d{2}" placeholder="введите цену заказа"/></td></tr>
                                <tr><td><label for="manufacturingPrice"> цена составляющих материалов</label></td>
                                    <td><input type="text" name="manufacturingPrice" pattern="\d{1,7}(\.|,)\d{2}" value="00.00"/></td></tr>
                                <tr><td><label for="isCompleted">состояние заказа</label></td>
                                    <td><input type="radio" name="isCompleted" value="0" checked/>не укомплектован<br>
                                    <input type="radio" name="isCompleted" value="1"/>укомплектован</td></tr>
                               <tr>
                                   <td><label for="isReady">степень готовности</label></td>
                                   <td><input type="radio" name="isReady" value="0" checked/>новый<br>
<!--                                       <input type="radio" name="isReady" value="1"/>закрыт успешно<br>-->
<!--                                       <input type="radio" name="isReady" value="2"/>закрыт неуспешно<br>-->
<!--                                       <input type="radio" name="isReady" value="3"/>запущен в производство</td>-->
                               </tr>
                               <tr style="display: none">
                                   <td><label for="isInstall">установлен у клиента</label></td>
                                   <td><input type="radio" name="isInstall" value="0" checked/>не установлен<br>
<!--                                       <input type="radio" name="isInstall" value="1"/>в процессе установки<br>-->
<!--                                       <input type="radio" name="isInstall" value="2"/>установлен</td>-->
                               </tr>
                               <tr>
                                   <td><label for="isDeposite" >предоплата</label></td>
                                   <td><input type="text" name="isDeposite" placeholder="грн.коп" pattern="\d{1,7}(\.|,)\d{2}"/></td></tr>
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
                                <tr style="display: none;">
                                    <td><label for="isAllowCalculateCost">разрешить менять цену комплектующих при изменении стоимости</label></td>
                                    <td><input type="radio" name="isAllowCalculateCost" value="0" /> не разрешать<br><input type="radio" name="isAllowCalculateCost" value="1" checked/>разрешать</td></tr>
                                </tbody>
                            </table>
                        </form>
                    <script>
                        $('form').submit(function(){
                            $(this).find('.alert .alert-info').remove();
//проверка данных отправляемых с формы
                            if($(this).find('select').val() == 0){
                                $(this).find('select').before('<div class="alert alert-info">выберитите клиента из выпадающего списка</div>');
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


</body>
</html>

