<?php
//можем здесь писать если просто вывод или пока что при подключении будет autoload.php в head.html
require_once '../autoload.php';
?>
<!DOCTYPE HTML>
<html>
<?php
//так работает
//     include_once ('E:\OpenServer\domains\reclama\head.html');
//     include_once ("$_SERVER[DOCUMENT_ROOT]/head.html");

   include_once('../head.html');
?>
<body>
<div class="container">
    <div class="row">
        <?php require_once("../templates/header.html"); ?>
        <?php //require_once("$_SERVER[DOCUMENT_ROOT]/templates/header.html"); ?>
    </div>
    <!-- навигация -->
    <div class="row">
        <?php require('../navigation.html');?>
        <script>
            showLi('задачи');
        </script>

    </div>
    <div class="row">
        <div class="col-lg-2 backForDiv">
            этот див слева от таблицы в нем можно расположить дополнительные кнопки добавить редактировать удалить
        </div>
        <div class="col-lg-10 backForDiv divForTable">
            <!--            <div id="divForTable">-->
            <?php
            //найдем все из таблицы поставщиков Supplier::findAll() и передадим на отображение через трэйт FastViewTAble.php showAllFromTable(findAll)
            echo \App\Models\Task::showAllFromTable('tableTasks', \App\Models\Task::findAll());
            ?>
        </div>
    </div>

</div>
</body>
</html>


