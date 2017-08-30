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
                            $tableAllMat = "<table id ='tbViewAllMaterials'><thead><tr><td style='display: none;'>id</td><td>название</td><td>доп характ</td><td>ед изм</td><td>форма поставки</td><td>цена за ед</td><td style='display: none;'>id поставщика</td><td>поставщик</td><td><span class='glyphicon glyphicon-edit'></span></td><td><span class=\"glyphicon glyphicon-trash\"></span></td></tr></thead><tbody>";
                            foreach ($allMaterialsInBase as $item){
//                                получим не false если есть этот материал хотябы в одном заказе
                                $ifExistOrderWithIdMaterial = \App\Models\MaterialsToOrder::ifExistThisMaterialInAnyOneOrder($item[id]);
//                                if($ifExistOrderWithIdMaterial )
//                                   echo "<br/> c idMaterials = $item[id] есть заказы )";
//                                else
//                                    echo "<br/>   c idMaterials = $item[id] нет  заказов ";

                                if($ifExistOrderWithIdMaterial){
                                    $tableAllMat .= "<tr><td style='display: none;'>$item[id]</td><td>$item[name]</td><td>$item[addCharacteristic]</td><td>$item[measure]</td><td>$item[deliveryForm]</td><td>$item[priceForMeasure]</td><td style='display: none;'>$item[idSupplier]</td><td>$item[nameSupplier]</td><td></td><td></td></tr>";
                                }
                                else{
                                    //получили false на запрос значит в заказах не используется это материал вствавим иконку удаления
                                    $tableAllMat .= "<tr><td style='display: none;'>$item[id]</td><td>$item[name]</td><td>$item[addCharacteristic]</td><td>$item[measure]</td><td>$item[deliveryForm]</td><td>$item[priceForMeasure]</td><td style='display: none;'>$item[idSupplier]</td><td>$item[nameSupplier]</td><td><a href='viewOneMaterial.php?id=$item[id]'><span class='glyphicon glyphicon-edit'></span></a></td><td data-id='$item[id]'><span class=\"glyphicon glyphicon-trash\"></span></td></tr>";
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

        <!-- модальное окно для удаления   -->
        <div id="modalWinForDeleteMat" class="modal fade" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header text-center">удалить материал навсегда!
                        <button class="close" data-dismiss="modal">закрыть</button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row" style="background-color: #c0c7d2;">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-lg-12 text-center">хотите удалить этот материал навсегда ?</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 text-center " id="modalNameMaterial"> название материала</div>
                                        <div style="display: block;" class="col-lg-12 text-center " id="modalIdMaterial"> id материала</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-center"><button name="btnDeleteMaterial" class="btn btn-danger">да</button></div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-center"><button class="btn btn-default" data-dismiss="modal">нет</button></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!--modal-body-->
                </div><!--modal content-->
            </div><!--modal-dialog-->
        </div><!--id="modalWinForDeleteMat" modal-fade -->

    </div>
    </body>
    </html>
<script>
    $(function () {
       //функция обработки клика на таблице будем обрабатыать только ячейки с наличием data-id то есть где можно удалить материал
        $('#tbViewAllMaterials').on('click',function () {
         var target = event.target;
           while (target.tagName != 'TABLE'){
               if(target.tagName == 'TD'){
                   //нашли ячейку где был клик
                   if($(target).data('id')){
                       console.log('id for delete '+$(target).data('id'));
                       //вызовем модальное окно для удаления ненужного материала
                       $('#modalIdMaterial').text( $(target).data('id') );
                       $('#modalNameMaterial').text( $(target).siblings()[1].textContent );
                       $('#modalWinForDeleteMat').modal('show');
                   }
               }
               target = target.parentNode;
           }
           console.log('click по таблице');
       });
        //функция обработки клика в модальном окне будем обрабатывать только кнопку
        $('#modalWinForDeleteMat').on('click',function () {
            var target = event.target;
            if(target.name == 'btnDeleteMaterial'){
                console.log('кликнули кнопку на удаление заказа');
                //будем удалять материал из базы
                jquery_send('.divForAnswerServer','post','../App/controllers/controllerViewAllMaterials.php',
                    ['deleteMaterialFromBase','idMaterial'],['',$('#modalIdMaterial').text()]);
                $('#modalIdMaterial').text('');
                $('#modalNameMaterial').text( '');
                $('#modalWinForDeleteMat').modal('hide');

            }
        });
        //функция обработки при вызове модального окна
        $('#modalWinForDeleteMat').on('show.bs.modal',function () {

        });


    });
</script>



