<?php
//можем здесь писать если просто вывод или пока что при подключении будет autoload.php в head.php
require './autoload.php';
////       echo get_called_class();
////        echo Нужно отметить, что для большего удобства в PHP кроме слова «static» есть еще специальная функция get_called_class(), которая сообщит вам — в контексте какого класса в данный момент работает ваш код.

                //                получим из таблицы всех поставщиков и покажем через вызов быстрого показа в трэйте FastViewTable.php
                echo $_GET["start_date"];
                echo \App\Models\Client::showAllFromTable('tableClient',\App\Models\Client::getByBirthday($_POST["start_date"],$_POST["end_date"]));
                ?>
				