<?php
//можем здесь писать если просто вывод или пока что при подключении будет autoload.php в head.html
require '../autoload.php';
?>
<!DOCTYPE HTML>
<html lang="ru-RU">
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
    <!--строка показа времени и показа результата добавки материала в базу  -->
    <?php  include_once '../App/html/forDisplayTimeShowAnswerServer.html'?>

    <div class="row"><!-- основной блок контета состоит из 2 колонок слева и 10 колонок справа -->
        <div class="col-lg-2 backForDiv"> <!-- начало доп блока слева-->
            этот див слева от таблицы в нем можно расположить дополнительные кнопки добавить редактировать удалить
        </div><!-- конец доп блока слева-->
        <div class="col-lg-10 backForDiv">
            <div class="row headingContent">
                <div class="col-lg-10   col-md-10 col-sm-10 col-xs-10   text-center "> поставщики</div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center"></div>
            </div>
            <div class="row rowSearch" ><!-- строка поиска-->
                <!--  сторка для поиска заказов по клиенту и по названию заказа -->
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <label for="inputFindMaterialForNameMaterial">искать по названию материала</label>
                    <input type="text" name="inputFindMaterialForNameMaterial" placeholder="по названию">
                    <button name="searchMaterialForNameMaterial" class="btn-primary">искать </button>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <label for="inputFindMaterialForNameSupplier">искать по названию поставщика</label>
                    <input type="text" name="inputFindMaterialForNameSupplier" placeholder="по поставщику">
                    <button name="searchMaterialForNameSupplier" class="btn-primary">искать</button>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                    <label for="makeNewSupplier"  class="text-center">новый поставщик</label>
                    <div title="создать нового поставщика" id="makeNewSupplier"></div>
                    <a href='formAddNewSupplierToBase.php'> <div class="text-center"> <span class='glyphicon glyphicon-plus'></span></div></a>
                </div>
            </div><!-- конец блока строки поиска  -->

            <div class="row backForDiv divForTable">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?php
                    //найдем всех поставщиков и отобразим их через таблицу
                    $allSuppliersInBase = \App\Models\Supplier::findAll();
                    if(! empty ($allSuppliersInBase)){
                        $tableAllSupp = "<table id='tbViewAllSuppliers'><thead><tr><td class='tdDisplayNone'>id</td><td>название</td><td>доп характ</td><td class='tdDisplayNone'>контакт</td><td>телефон</td><td class='tdDisplayNone'>email</td><td>адрес поставщика</td><td class='tdDisplayNone'>день доставки</td><td class='tdDisplayNone'>сайт</td><td class='text-center'><span class='glyphicon glyphicon-eye-open'></span></td><td class='text-center'><span class='glyphicon glyphicon-trash'></span></td></tr></thead><tbody>";
                        foreach ($allSuppliersInBase as $item){
                            //найдем idMaterial для каждого поставщика, чтобы узнать есть или нет эти материалы в заказах и
                            // разрешать удалять только тех поставщиков, чьих материалов нет в заказах
                            if(\App\Models\MaterialsToOrder::ifExistThisSupplierInAnyMaterilsToOrder($item->id)){
//                                есть материал этото поставщика хотябы в одном заказе поэтому не будем разрешать удалять поставщика
                                $tableAllSupp .= "<tr><td class='tdDisplayNone'>$item->id</td><td>$item->name</td><td>$item->addCharacteristic</td><td class='tdDisplayNone'>$item->contactPerson</td><td>$item->phone0</td><td class='tdDisplayNone'>$item->email0</td><td>$item->address</td><td class='tdDisplayNone'> ".$item->getDeliveryDays()." </td><td class='tdDisplayNone'><a href='$item->site' target='_blank'>$item->site</a></td><td class='text-center'><a href='viewOneSupplier.php?id=$item->id'><span class='glyphicon glyphicon-eye-open'></span></a></td><td></td></tr>";
                            }
                            else{
                                // нет материалов этого поставщика, поэтому разрешим его удаление
                                $tableAllSupp .= "<tr><td class='tdDisplayNone'>$item->id</td><td>$item->name</td><td>$item->addCharacteristic</td><td class='tdDisplayNone'>$item->contactPerson</td><td>$item->phone0</td><td class='tdDisplayNone'>$item->email0</td><td>$item->address</td><td class='tdDisplayNone'> ".$item->getDeliveryDays()." </td><td class='tdDisplayNone'><a href='$item->site' target='_blank'>$item->site</a></td><td class='text-center'><a href='viewOneSupplier.php?id=$item->id'><span class='glyphicon glyphicon-eye-open'></span></a></td><td data-id_supplier='$item->id' class='text-center'><span class='glyphicon glyphicon-trash'></span></td></tr>";
                            }
                        }
                        $tableAllSupp .= "</tbody></table>";
                    }
                    else{
                        $tableAllSupp = "пока ничего нет (";
                    }
                    echo "$tableAllSupp";
                    ?>
                </div>
            </div>
    </div>
        <!-- модальное окно для удаления   -->
        <div id="modalWinForDeleteSupp" class="modal fade" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header text-center">удалить поставщика навсегда!
                        <button class="close" data-dismiss="modal">закрыть</button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row" style="background-color: #c0c7d2;">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-lg-12 text-center">хотите удалить этотого поставщика навсегда ?</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 text-center " id="modalNameSupplier"> название поставщика</div>
                                        <div style="display: none;" class="col-lg-12 text-center " id="modalIdSupplier"> id поставщика</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-center"><button name="btnDeleteSupplier" class="btn btn-danger">да</button></div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-center"><button class="btn btn-default" data-dismiss="modal">нет</button></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!--modal-body-->
                </div><!--modal content-->
            </div><!--modal-dialog-->
        </div><!--id="modalWinForDeleteMat" modal-fade -->

</div><!-- container -->
</body>
</html>
<script type='text/javascript'>
    $(function () {
        //функция обработки клика на таблице будем обрабатыать только ячейки с наличием data-id то есть где можно удалить материал
        $('#tbViewAllSuppliers').on('click',function (event) {
            var target = event.target;
            while (target.tagName != 'TABLE'){
                if(target.tagName == 'TD'){
                    //нашли ячейку где был клик
                    if($(target).data('id_supplier')){
                        console.log('id for delete '+$(target).data('id_supplier'));
                        //вызовем модальное окно для удаления ненужного материала
                        $('#modalIdSupplier').text( $(target).data('id_supplier') );
                        $('#modalNameSupplier').text( $(target).siblings()[1].textContent );
                        $('#modalWinForDeleteSupp').modal('show');
                    }
                }
                target = target.parentNode;
            }
            console.log('click по таблице');
        });
        //функция обработки клика в модальном окне будем обрабатывать только кнопку
        $('#modalWinForDeleteSupp').on('click',function (event) {
            var target = event.target;
            if(target.name == 'btnDeleteSupplier'){
                console.log('кликнули кнопку на удаление заказа');
                //будем удалять материал из базы
                jquery_send('.divForAnswerServer','post','../App/controllers/controllerViewAllSuppliers.php',
                    ['deleteSupplierFromBase','idSupplier'],['',$('#modalIdSupplier').text()]);
                $('#modalIdSupplier').text('');
                $('#modalNameSupplier').text( '');
                $('#modalWinForDeleteSupp').modal('hide');

            }
        });
        //функция обработки при вызове модального окна
        $('#modalWinForDeleteMat').on('show.bs.modal',function () {

        });


    });
</script>

