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
            <?php include('../navigation.html');?>
            <script>
                showLi('материалы');
            </script>

            <!-- конец навигации -->
        </div>
        <div class="row">
            <!--            начало доп блока слева-->
            <div class="col-lg-2 backForDiv">
                этот див слева от таблицы в нем можно расположить дополнительные кнопки добавить редактировать удалить
            </div>
            <!--            конец доп блока слева-->
            <div class="col-lg-10 backForDiv divForTable">
                <?php
                //найдем все материалы и отобразим их через таблицу через трэйт FastViewTable.php
                echo \App\Models\Material::showAllFromTable('tableMaterials', \App\Models\Material::findAll());
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2"></div>
            <div class="col-lg-10">
                <div class="row">
                    <col class="lg-12 text-center"> материалы поставщики по имени и ссылка поставщика</div>
                <div class="row">
                    <div class="col-lg-12">

                    </div>
                </div>
            </div>
        </div>

    </div>
    </body>
    </html>
<?php


