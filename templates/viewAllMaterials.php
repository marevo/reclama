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
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <?php

                        //найдем все материалы и отобразим их через таблицу через трэйт FastViewTable.php
                        //                echo \App\Models\Material::showAllFromTable('tableMaterials', \App\Models\Material::findAll());
                        //                найдем все материалы с названиями поставщиков
                        $allMaterialsInBase = \App\Models\Material::selectForView();
                        if(! empty ($allMaterialsInBase)){
                            $tableAllMat = "<table><thead><tr><td>id</td><td>название</td><td>доп характ</td><td>ед изм</td><td>форма поставки</td><td>цена за ед</td><td>id поставщика</td><td>поставщик</td><td>править</td><td>удалить</td></tr></thead><tbody>";
                            foreach ($allMaterialsInBase as $item){
//                                получим не false если есть этот материал хотябы в одном заказе
                                $ifExistOrderWithIdMaterial = \App\Models\MaterialsToOrder::ifExistThisMaterialInAnyOneOrder($item[id]);
                                if($ifExistOrderWithIdMaterial )
                                   echo "<br/> c idMaterials = $item[id] есть заказы )";
                                else
                                    echo "<br/>   c idMaterials = $item[id] нет  заказов ";

                                if($ifExistOrderWithIdMaterial){
                                    $tableAllMat .= "<tr><td>$item[id]</td><td>$item[name]</td><td>$item[addCharacteristic]</td><td>$item[measure]</td><td>$item[deliveryForm]</td><td>$item[priceForMeasure]</td><td>$item[idSupplier]</td><td>$item[nameSupplier]</td><td>нельзя править</td><td>нельзя удалять</td></tr>";
                                }
                                else{
                                    //получили false на запрос естьи данный материал хотябы в одном заказе
                                    $tableAllMat .= "<tr><td>$item[id]</td><td>$item[name]</td><td>$item[addCharacteristic]</td><td>$item[measure]</td><td>$item[deliveryForm]</td><td>$item[priceForMeasure]</td><td>$item[idSupplier]</td><td>$item[nameSupplier]</td><td><a href='viewOneMaterial.php?id=$item[id]'>править</a></td><td>удалить</td></tr>";
                                }
                            }
                            $tableAllMat .= "</tbody></table>";
                        }
                        else{
                            $tableAllMat = "пока ничего нет (";
                        }
                        echo "$tableAllMat";
                        ?>  
                    </div>
                </div>
               
            </div>
        </div>


    </div>
    </body>
    </html>
<?php


