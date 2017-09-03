<?php
//можем здесь писать если просто вывод или пока что при подключении будет autoload.php в head.html
require '../autoload.php';
////       echo get_called_class();
////        echo Нужно отметить, что для большего удобства в PHP кроме слова «static» есть еще специальная функция get_called_class(), которая сообщит вам — в контексте какого класса в данный момент работает ваш код.
?>
<!DOCTYPE HTML>
 <html lang="ru-RU">
    <?php
       include('../head.html');
    ?>
    <body>
    <div class="container">
        <div class="row">
            <?php require_once('header.html'); ?>
        </div>
<!--        добавление панели навигации-->
        <div class="row"><!-- навигация -->
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?php
//                     include ('/E:/OpenServer/domains/reclama/navigation.html');
                     require_once('../navigation.html');
                ?>
                <script>
                    showLi('клиенты');
                </script>

            </div>
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
                //                получим из таблицы всех поставщиков и покажем через вызов быстрого показа в трэйте FastViewTable.php

                echo \App\Models\Client::showAllFromTable('tableClient',\App\Models\Client::findAll());
                ?>
            </div>
        </div>

    </div>
    </body>
    </html>
<?php


