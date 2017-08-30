<?php
//можем здесь писать если просто вывод или пока что при подключении будет autoload.php в head.php
require '../autoload.php';
?>
<div class="row">
        <div class="col-lg-2 backForDiv">
            этот див слева от таблицы в нем можно расположить дополнительные кнопки добавить редактировать удалить
        </div>
        <div class="col-lg-10 backForDiv divForTable">
            <!--            <div id="divForTable">-->
            <?php
            //найдем все из таблицы поставщиков Supplier::findAll() и передадим на отображение через трэйт FastViewTAble.php showAllFromTable(findAll)
            echo \App\Models\Report::showAllFromTable('tableReports', \App\Models\Report::findAll());
            ?>
        </div>
</div>