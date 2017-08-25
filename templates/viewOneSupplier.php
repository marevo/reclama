<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 12.08.2017
 * Time: 17:41
 */
require '../autoload.php';
//$IDSUPPLIER = 0;
if(isset($_GET['id'])){
//    если передали id значит работаем с ним иначе будем брать в else по умолчанию id=1
    $IDSUPPLIER = $_GET['id'];
}
else{
    $IDSUPPLIER = 1;
}
$supplier = \App\Models\Supplier::findObjByIdStatic($IDSUPPLIER);
//echo var_dump($supplier);
?>


<!DOCTYPE HTML>
<html>
<title> просмотр данных поставщика </title>
<?php include('../head.html') ?>
<body>
<div class="container">
    <div class="row">*header* //не забыть удалить
        <?php require_once('header.html'); ?> строка 1 для header.php
    </div>
    <div class="row"><!-- навигация -->
        <?php include('../navigation.html');?>строка 2 для navigation.php
        <script>
            showLi('');
        </script>
    </div>
    <?php
    //подключение модального окна для просмотра доп сведений о поставщике его еще надо написать **/**
//    include_once('viewAllMaterialsToOrder.html');

    ?>
    <div class="row">строка 3
        <!--рабочее место слева для будущего меню-->
        <div class="col-lg-2 backForDiv">
            этот див слева от таблицы в нем можно расположить дополнительные кнопки добавить редактировать удалить
        </div>
        <!--/рабочее место слева для будущего меню-->

        <!--рабочее место справа-->
        <div class="col-lg-10 backForDiv divForTable">для таблицы
            <?php echo //передаем объект Supplier для распределения
            "<script>
 var SUPPLIER = {
     id : '$supplier->id',
     name: '$supplier->name',
     addCharacteristic : '$supplier->addCharacteristic',
     phone0 : '$supplier->phone0',
     email0 : '$supplier->email0',
     contactPerson : '$supplier->contactPerson',
     address : '$supplier->address',
     deliveryDay : '$supplier->deliveryDay',
     site : '$supplier->site'
 };
 </script>
             ";
            ?>
            <div class="row"><!--просмотр одного поставщика-->
                поставщик:<div class="col-lg-12 bg-primary panel-info h3 data-name='name' " >
<!--                    --><?php //echo $supplier->name; ?>
                </div>
            </div>
            <?php require_once ('../App/html/forDisplayTimeShowAnswerServer.html');?>
            <div class="row"><!--дата и отображение результатов запросов к серверу -->
                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-4">сегодня <span id = 'dateToday'></span></div>
                <div class="col-lg-4 col-md-4 col-sm-8 col-xs-8" id="rezZaprosaKServerViewOneSupplier" ></div><!-- сделать повесить сюда -->
                <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12" id="rezShow"> что пришло с сервера</div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <table id="tableOneSupplier" >
                        <thead><tr><td>данные поставщика</td><td>значение</td><td><span class="glyphicon glyphicon-plus-sign"></span> добавить поставщика
                            </td></tr></thead>
                        <tbody>
                        <tr><td>название поставщика</td><td data-name="name"></td><td><span class="glyphicon glyphicon-edit"> править</span></td></tr>
                        <tr><td>дополнительные характеристики</td><td data-name="addCharacteristic"></td><td><span class="glyphicon glyphicon-edit"> править</span></td></tr>
                        <tr><td>телефон</td><td data-name="phone0"></td><td><span class="glyphicon glyphicon-edit"> править</span></td></tr>
                        <tr><td>email</td><td data-name="email0"></td><td><span class="glyphicon glyphicon-edit"> править</span></td></tr>
                        <tr><td>контактная персона</td><td data-name="contactPerson"></td><td><span class="glyphicon glyphicon-edit"> править</span></td></tr>
                        <tr><td>адрес</td><td data-name="address"></td><td><span class="glyphicon glyphicon-eye-open"> править</span></td></tr>
                        <tr><td>день доставки</td><td data-name="deliveryDay" ></td><td><span class="glyphicon glyphicon-edit"> править</span></td></tr>
                        <tr><td>сайт</td><td data-name='site'></td><td><span class="glyphicon glyphicon-edit"> править</span></td></tr>
                        </tbody>
                    </table>
                    <script>
                        //*/на событие загрузки таблицы добавили на саму таблицу click функцию al
                        //при загрузке страницы в '#dateToday' будет выведена сегодняшняя дата в формате yyyy-mm-dd
                        document.addEventListener('DOMContentLoaded',function () {
                            var date = getDate();
                            $('.dateToday').html(date)  ;
                            dateToday = date;
                            idClient = $('#idClient').html();
                        });

                    </script>

                </div>
            </div>
        </div>
        <!-- конец рабочей зоны -->
    </div>



</div><!-- container-->
<script src="../js/viewOneSupplier.js"></script>
</body>
</html>
