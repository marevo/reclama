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
    <!--строка показа времени и показа результата добавки материала в базу  -->
    <?php  include_once '../App/html/forDisplayTimeShowAnswerServer.html'?>

    <div class="row"><!-- основной блок контета состоит из 2 колонок слева и 10 колонок справа -->
        <div class="col-lg-2 backForDiv"> <!-- начало доп блока слева-->
            этот див слева от таблицы в нем можно расположить дополнительные кнопки добавить редактировать удалить
        </div><!-- конец доп блока слева-->
        <div class="col-lg-10 backForDiv">
            <!--         <div class="row headingContent">
                <div class="col-lg-10   col-md-10 col-sm-10 col-xs-10   text-center ">все поставщики</div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center"><a class="a_displayBlock" href="formAddNewSupplierToBase.php"><span class="glyphicon glyphicon-plus"></span> добавить поставщика<span>  </a></div>
            </div>
            -->
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
                        $tableAllSupp = "<table><thead><tr><td>id</td><td>название</td><td>доп характ</td><td>контакт</td><td>телефон</td><td>email</td><td>адрес поставщика</td><td>день доставки</td><td>сайте</td><td><span class='glyphicon glyphicon-eye-open'></span></td><td><span class='glyphicon glyphicon-trash'></span></td></tr></thead><tbody>";
                        foreach ($allSuppliersInBase as $item){
                            $tableAllSupp .= "<tr><td>$item->id</td><td>$item->name</td><td>$item->addCharacteristic</td><td>$item->contactPerson</td><td>$item->phone0</td><td>$item->email0</td><td>$item->address</td><td> ".$item->getDeliveryDays()." </td><td><a href='$item->site' target='_blank'>$item->site</a></td><td><a href='viewOneSupplier.php?id=$item->id'>править</a></td><td>удалить</td></tr>";
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

</div><!-- container -->
</body>
</html>

7
