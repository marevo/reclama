<?php
//можем здесь писать если просто вывод или пока что при подключении будет autoload.php в head.html
require '../autoload.php';
?>
<!DOCTYPE HTML>
    <html>
<?php
    require_once('../head.html');
?>
<body>
<div class="container">
    <div class="row">
        <?php require_once('header.html'); ?>
    </div>
    <div class="row"><!-- навигация -->
        <?php  include('../navigation.html');?>
        <script>
            showLi('поставщики');
        </script>

    </div>
    <div class="row">
        <div class="col-lg-2 backForDiv">
           этот див слева от таблицы в нем можно расположить дополнительные кнопки добавить редактировать удалить
        </div>
        <div class="col-lg-10 backForDiv divForTable">
<!--            <div id="divForTable">-->
                <?php
//                получим из таблицы всех поставщиков
//                $allSuppliers = \App\Models\Supplier::getAllSuppliers();
                //or this side
//                $allSuppliers = \App\Models\Supplier::findAll();
//просмотр результата что вернет вызов $allSuppliers
//                var_dump($allSuppliers);
                //передадим из в функцию показать всех поставщико showAllSuppliers(allSuppliers)
//                echo  showAllSupliers($allSuppliers);
                //найдем все из таблицы поставщиков Supplier::findAll() и передадим на отображение через трэйт FastViewTAble.php showAllFromTable(findAll)
                echo \App\Models\Supplier::showAllFromTable('tableSuppliers', \App\Models\Supplier::findAll());
                ?>
<!--            </div>-->
        </div>
    </div>

</div>
</body>
</html>


