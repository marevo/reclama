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
            <div class="col-lg-10 backForDiv">
            <!--строка показа времени и показа результата добавки материала в базу  -->
            <?php  include_once '../App/html/forDisplayTimeShowAnswerServer.html'?>
            <div class="row headingContent">
                <div class="col-lg-10   col-md-10 col-sm-10 col-xs-10   text-center ">все материалы что есть в базе материалов</div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center"><a class="a_displayBlock" href="formAddNewMaterialsToBase.php"><span class="glyphicon glyphicon-plus"></span> <span>добавить новый материал в базу</span></a></div>
            </div>
                <?php
                //найдем все материалы и отобразим их через таблицу через трэйт FastViewTable.php
                echo \App\Models\Material::showAllFromTable('tableMaterials', \App\Models\Material::findAll());
                ?>
            </div>
        </div>


    </div>
    </body>
    </html>
<?php


