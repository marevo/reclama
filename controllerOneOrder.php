<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 05.07.2017
 * Time: 16:12
 */
namespace App;
use App\Models\Client;
use App\Models\Material;
use App\Models\MaterialsToOrder;
use App\Models\Order;
use App\Models\Supplier;

// use App\ModelLikeTable; //не забыть удалить

require ('autoload.php');

//addSum добавление суммы оплаты по заказу
if(isset($_GET['addSum'])){
    if(isset($_GET['sum']))
        $sum = htmlspecialchars($_GET['sum']);
    if(isset($_GET['idOrder']))
        $idOrder = intval($_GET['idOrder']);
    if(isset($_GET['idClient']))
        $idClient = intval($_GET['idClient']);
    if(isset($_GET['dateToday']))
        $dateUpdateSum = htmlspecialchars($_GET['dateToday']);
//    echo "idOrder = $idOrder, idClient = $idClient sum = $sum  dateToday = $dateUpdateSum";
//die();//не забыть удалить
    $payment = new \App\Models\Payment();
    $payment->idOrder = $idOrder;
    $payment->idClient = $idClient;
    $payment->date = $dateUpdateSum;
    $payment->sumPayment = $sum;
    $resInsert = $payment->insert();
    //после пробы добавить оплату мы должны зановосчитать оплату по idOrder
    $payment =  \App\Models\Payment::showSumAllPayments($idOrder);

    if($resInsert == false ){
        //не удалось добавить оплату
        echo "
            <script>
            ORDER['sumAllPayments'] = $payment;
            fNoUspeh();
            </script>
        ";
    }
    else{
//        удалось добавить оплату
        echo "  
            <script>
            ORDER['sumAllPayments'] = $payment;
            fUspeh();
            </script>
        ";
    }
}
//* загрузка по запросу всех клиентов в select и отправка на страницу viewOneOrder.php id='forClearNameClient'
if(isset($_GET['selectAllClients'])){
            $allClients = Client::findAll();
            $options ="<option value='0'>выберите клиента</option>";
            foreach ($allClients as $client  ){
                $options .= "<option value='$client->id'>$client->name</option>";
            }
            echo "$options";
}
//*/загрузка по запросу всех клиентов в select и отправка на страницу viewOneOrder.php id='forClearNameClient'

//внесение изменений в поле заказа вызывает ф
if(isset($_GET['update'])){
    if(isset($_GET['idOrder']))
        $idOrder = intval($_GET['idOrder']);
    if(isset($_GET['nameField']))
        $nameField = htmlspecialchars($_GET['nameField']);
    if(isset($_GET['valueField']))
        $valueField = htmlspecialchars($_GET['valueField']);
    switch ($nameField){
        ///название заказа
        case 'nameOrder':
            $or = Order::findObjByIdForViewOneOrder($idOrder);
            $or->name = $valueField;
            $res = $or->update();
            //найдем в базе этот заказ и передадим его название старое или новое неважно
            $or = Order::findObjByIdForViewOneOrder($idOrder);
            echo "$or->name 
            <script>
            ORDER['$nameField'] = '$or->name';
            </script>
            ";
            //запустим функцию отображения ответа сервера (если успешно поменяли/ или если не успешно поменяли)
            if($res == true){
                echoUspeh();
            }
            if($res == false){
                echoNoUspeh();
            }
            break;
        //описание заказа
        case 'descriptionOrder':
            $or = Order::findObjByIdForViewOneOrder($idOrder);
            $or->descriptionOrder = $valueField;
            $res = $or->update();
            //найдем в базе этот заказ и передадим его название старое или новое неважно
            $or = Order::findObjByIdForViewOneOrder($idOrder);
            echo "$or->descriptionOrder 
            <script>
            ORDER['$nameField'] =' $or->descriptionOrder';
            </script>
            ";
            //запустим функцию отображения ответа сервера (если успешно поменяли/ или если не успешно поменяли)
            if($res == true){
                echoUspeh();
            }
            if($res == false){
                echoNoUspeh();
            }
            break;

        case 'source':
            $or = Order::findObjByIdForViewOneOrder($idOrder);
            $or->source = $valueField;
            $res = $or->update();
            //найдем в базе этот заказ и передадим его название старое или новое неважно
            $or = Order::findObjByIdForViewOneOrder($idOrder);
            echo " 
            <script>
            ORDER['$nameField'] ='$or->source';
            </script>
            ";
            //запустим функцию отображения ответа сервера (если успешно поменяли/ или если не успешно поменяли)
            if($res == true){
                echoUspeh();
            }
            if($res == false){
                echoNoUspeh();
            }
            break;
        //укомплектован заказ или не укомплектован
        case 'isCompleted':
            $or = Order::findObjByIdForViewOneOrder($idOrder);
            $or->isCompleted = $valueField;
            $res = $or->update();
            //найдем в базе этот заказ и передадим его название старое или новое неважно
            $or = Order::findObjByIdForViewOneOrder($idOrder);
            echo " 
            <script>
            ORDER['$nameField'] ='$or->isCompleted';
            </script>
            ";
            //запустим функцию отображения ответа сервера (если успешно поменяли/ или если не успешно поменяли)
            if($res == true){
                echoUspeh();
            }
            if($res == false){
                echoNoUspeh();
            }
            break;
        case 'isReady':
            $or = Order::findObjByIdForViewOneOrder($idOrder);
            $or->isReady = $valueField;
            $res = $or->update();
            //найдем в базе этот заказ и передадим его название старое или новое неважно
            $or = Order::findObjByIdForViewOneOrder($idOrder);
            echo " 
            <script>
            ORDER['$nameField'] ='$or->isReady';
            </script>
            ";
            //запустим функцию отображения ответа сервера (если успешно поменяли/ или если не успешно поменяли)
            if($res == true){
                echoUspeh();
            }
            if($res == false){
                echoNoUspeh();
            }
            break;

        case 'isInstall':
            $or = Order::findObjByIdForViewOneOrder($idOrder);
            $or->isInstall = $valueField;
            $res = $or->update();
            //найдем в базе этот заказ и передадим его название старое или новое неважно
            $or = Order::findObjByIdForViewOneOrder($idOrder);
            echo " 
            <script>
            ORDER['$nameField'] ='$or->isInstall';
            </script>
            ";
            //запустим функцию отображения ответа сервера (если успешно поменяли/ или если не успешно поменяли)
            if($res == true){
                echoUspeh();
            }
            if($res == false){
                echoNoUspeh();
            }
            break;
        case 'isAllowCalculateCost':
            $or = Order::findObjByIdForViewOneOrder($idOrder);
            $or->isAllowCalculateCost = $valueField;
            $res = $or->update();
            //найдем в базе этот заказ и передадим его название старое или новое неважно
            $or = Order::findObjByIdForViewOneOrder($idOrder);
            echo " 
            <script>
            ORDER['$nameField'] ='$or->isAllowCalculateCost';
            </script>
            ";
            //запустим функцию отображения ответа сервера (если успешно поменяли/ или если не успешно поменяли)
            if($res == true){
                echoUspeh();
            }
            if($res == false){
                echoNoUspeh();
            }

            break;
        case 'isTrash':
            $or = Order::findObjByIdForViewOneOrder($idOrder);
            $or->isTrash = $valueField;
            $res = $or->update();
            //найдем в базе этот заказ и передадим его название старое или новое неважно
            $or = Order::findObjByIdForViewOneOrder($idOrder);
            echo " 
            <script>
            ORDER['$nameField'] ='$or->isTrash';
            </script>
            ";
            //запустим функцию отображения ответа сервера (если успешно поменяли/ или если не успешно поменяли)
            if($res == true){
                echoUspeh();
            }
            if($res == false){
                echoNoUspeh();
            }
            break;
        case 'dateOfOrdering':
            $or = Order::findObjByIdForViewOneOrder($idOrder);
            $or->dateOfOrdering = $valueField;
            $res = $or->update();
            //найдем в базе этот заказ и передадим его название старое или новое неважно
            $or = Order::findObjByIdForViewOneOrder($idOrder);
            echo " 
            <script>
            ORDER['$nameField'] ='$or->dateOfOrdering';
            </script>
            ";
            //запустим функцию отображения ответа сервера (если успешно поменяли/ или если не успешно поменяли)
            if($res == true){
                echoUspeh();
            }
            if($res == false){
                echoNoUspeh();
            }
            break;
        case 'dateOfComplation':
            $or = Order::findObjByIdForViewOneOrder($idOrder);
            $or->dateOfComplation   = $valueField;
            $res = $or->update();
            //найдем в базе этот заказ и передадим его название старое или новое неважно
            $or = Order::findObjByIdForViewOneOrder($idOrder);
            echo " 
            <script>
            ORDER['$nameField'] ='$or->dateOfComplation';
            </script>
            ";
            //запустим функцию отображения ответа сервера (если успешно поменяли/ или если не успешно поменяли)
            if($res == true){
                echoUspeh();
            }
            if($res == false){
                echoNoUspeh();
            }
            break;
        //смена клиента для которого делается заказ
        case 'idClient':
            $client = Client::findObjByIdStatic($valueField);
            if($client !='null'){
                echo "
                  <script>
                  ORDER['$nameField']= '$valueField';
                  ORDER['nameClient']= '$client->name';
                  fUspeh();
                  </script>";
            }
            else{
                echo "
                <script>
                fNoUspeh();
                  ORDER['$nameField']= '0';
                  ORDER['nameClient']='ошибка обратитесь к разработчику';
                </script>
                ";
            }
            break;
//изменение цены комплектующих заказа
        case 'manufacturingPrice':
            $or = Order::findObjByIdForViewOneOrder($idOrder);
            $or->manufacturingPrice = $valueField;
            $res = $or->update();
            if($res == true){
                echo "<script>ORDER['$nameField']= '$valueField';</script>";
                echo"<script>fUspeh();</script>";
            }
            if($res == false){
                $manPrice = Order::findObjByIdForViewOneOrder($idOrder)->manufacturingPrice;
                echo "<script>ORDER['$nameField']= '$manPrice';</script>";
                echo"<script>  fNoUspeh();</script>";
            }

            break;
//изменение стоимости заказа
        case 'orderPrice':
            $or = Order::findObjByIdForViewOneOrder($idOrder);
            $or->orderPrice = $valueField;
            $res = $or->update();
            if($res == true){
                echo "
                <script>
                  ORDER['$nameField']= '$valueField';
                  fUspeh();
                  </script>
                ";
            }
            if($res == false){
                $orPrice = Order::findObjByIdForViewOneOrder($idOrder)->orderPrice;
                echo "
                <script>
                  ORDER['$nameField']= '$orPrice';
                  fNoUspeh();
                  </script>
                ";
            }
        break;
        default:
            echoNoUspeh();
            break;
    }
//die();
}

//обработка вызова из базы (materialsToOrder)список всех  материалов к заказу
if(isset($_POST['getAllMaterialsForOrder'])){
    if(isset($_POST['idOrder'])){
        $idOrder = intval($_POST['idOrder']);
        $allMaterialsForThisOrder = MaterialsToOrder::getAllMaterialsToOrder($idOrder);
        if(empty($allMaterialsForThisOrder)){
            echo " в этом заказе пока нет материалов  !!!";
            die();
        }
        if($allMaterialsForThisOrder != false){ //если не пусто в списке материалов для этого заказа
            $table ="<thead><tr>
            <td>id</td>
            <td>idOrder</td>
            <td>idMaterials</td>
            <td>nameMaterials</td>
            <td>countNeed</td>
            <td>priceCountNeed</td>
            <td>recomAddCount</td>
            <td>priceRecomNeed</td>
            <td><button data-name ='addMaterial' name = 'addMaterial'><span class='glyphicon glyphicon-plus'> добавить материал</span></button></td>
            <td>новое количество<br/>материала</td>
            <td>править</td>
            <td>удалить</td>
            </tr></thead><tbody>";
            foreach ($allMaterialsForThisOrder as $value){
               $table.= "
               <tr><td> $value->id </td>
               <td> $value->idOrder </td>
               <td> $value->idMaterials </td>
               <td>". Material::findObjByIdStatic($value->idMaterials)->name ." </td>
               <td> $value->countNeed </td>
               <td> $value->priceCountNeed </td>
               <td> $value->recomAddCount </td>
               <td> $value->priceRecomNeed </td>
               <td></td>
               <td><input type='text' size='7'></td>
               <td class='updateThisCountMaterial'><a><span class='glyphicon glyphicon-edit'> править</span></a></td>
               <td class='deleteThisMaterialFromThisOrder'>
                   <a><span class='glyphicon glyphicon-trash'> удалить</span></a></td>
               </tr>";
            }
            $objOrder = Order::findObjByIdStatic( $idOrder);
            $manufacturingPriceRecom = $objOrder->getManufacturingPriceRecom();
            $manufacturingPriceCount = $objOrder->getManufacturingPriceCount();
            $table.= "<tr> <td colspan='5' class='fontSizeMedium' >расчетная стоимость комплектующих </td><td class='fontSizeMedium manufacturingPriceCount'>$manufacturingPriceCount</td><td colspan='2'></td> </tr>";
            $table.= "<tr> <td colspan='7' class='fontSizeMedium' >рекомендуемая стоимость комплектующих </td><td class='fontSizeMedium manufacturingPriceRecom'>$manufacturingPriceRecom </td> </tr>";
            $table.="</tbody>";
            //выбрасываем таблицу в 
            echo "$table";
        }
        else{
            echo "<thead></thead><tbody>пока нет добавленных к заказу материалов</tbody>";
        }
    }
}

//вывод в модальное окно     id="tableFildMaterialToAddToOrder" всех материалов что есть в базе
if(isset($_POST['getAllMaterialsFromBase'])){
    if(isset($_POST['idOrder'])){
        $idOrder = intval($_POST['idOrder']);
        $allMaterialsFromBase = Material::findAll();
        if($allMaterialsFromBase == false){
            echo"<thead></thead><tbody>пока нет добавленных к заказу материалов</tbody>";
            die();
        }
        else{
            $table ="<thead><tr>
            <td>id</td>
            <td>name</td>
            <td>addCharacteristic</td>
            <td>measure</td>
            <td>deliveryForm</td>
            <td>priceForMeasure</td>
            <td>id_suppliers</td>
            <td>nameSuppliers</td>
            </tr></thead><tbody>";
            foreach ($allMaterialsFromBase as $value){
                $table.= "
               <tr><td> $value->id </td>
               <td> $value->name </td>
               <td> $value->addCharacteristic </td>
               <td> $value->measure </td>
               <td> $value->deliveryForm </td>
               <td> $value->priceForMeasure </td>
               <td> $value->id_suppliers </td>
               <td> ".Supplier::findObjByIdStatic($value->id_suppliers)->name ." </td>
               </tr>";
            }
            $table.="</tbody>";
            echo "$table";
        }
    }
    else{
        echo"<thead></thead><tbody>ошибка в передаче данных обратитесь к разработчику</tbody>";
    }
}
//*вывод на клиент показа yt успешного запроса в базу
function echoUspeh()
{
    echo "<script>fUspeh();</script>";
}
//*/вывод на клиент показа не успешноо запроса в базу
//**вывод на клиент показа успешного запроса в базу
function echoNoUspeh()
{
    echo "<script>fNoUspeh();</script>";
}
//*/вывод на клиент показа не успешного запроса в базу

//**поиск по назаванию материала и вывод в таблицу в модальное окно addMaterial.php оно входит в viewOneOrder.php
if(isset($_POST['searchMaterialsForName'])){
    if(isset($_POST['nameMaterialLike'])){
        $nameMaterialLike = htmlspecialchars($_POST['nameMaterialLike']);
//        echo "название пришло: $nameMaterialLike";
        $searchedMaterials = Material::searchAllForLikeName($nameMaterialLike);
        echo ("нашли материалы $searchedMaterials");
        var_dump("$searchedMaterials");
        if($searchedMaterials == false){
            echoNoUspeh();
            echo"<thead></thead><tbody>пока нет материалов с таким названием</tbody>";
        }
        else
        {
            $table ="<thead><tr>
            <td>id материала</td>
            <td>название материала</td>
            <td>доп характеристики</td>
            <td>единица измериния</td><!-- м - поставка в метарах погонных-->
            <td>форма поставки</td><!--допусим труба идет поставкой длиной 2.86 м -->
            <td>цена за единицу</td> <!--цена за единицу поставки в метрах погонных -->
            <td>id поставщика</td>
            <td>поставщик</td>
            <td>количество</td>
            <td><span class = 'glyphicon glyphicon-plus'>нажмите добавить напротив нужного материала</span></td>
            </tr></thead><tbody>";
            foreach ($searchedMaterials as $value){
                $table.= "
               <tr><td> $value->id </td>
               <td> $value->name </td>
               <td> $value->addCharacteristic </td>
               <td> $value->measure </td>
               <td> $value->deliveryForm </td>
               <td> $value->priceForMeasure </td>
               <td> $value->id_suppliers <a href='viewOneSupplier.php?id=$value->id_suppliers'>посмотреть поставщика</a></td>
               <td> ".Supplier::findObjByIdStatic($value->id_suppliers)->name ." </td>
               <td><input size='7' placeholder='количество' class='inputToControle'/></td>
               <td><button class='btn btn-primary addMaterialToOrder'><span class = 'glyphicon glyphicon-plus'>добавить к заказу</span></button> </td>
               </tr>";
            }
            $table.="</tbody>";
            echo "$table";
            echoUspeh();
        }
    }
}
//*добавление материала к заказу
if(isset($_POST['addCountMaterialToOrder'])){
    echo "запрос в базу на добавление материала";//не забыть удалить

    if(isset($_POST['idOrder']))
        $idOrder = intval($_POST['idOrder']);
    if(isset($_POST['idMaterial']))
        $idMaterial=$_POST['idMaterial'];
    if(isset($_POST['countMaterial']))
        $countMaterial=$_POST['countMaterial'];
//    die("idOrder = $idOrder  idMaterial = $idMaterial countMaterial = $countMaterial");
    $materToOrder = new MaterialsToOrder();
    $materToOrder->idOrder = $idOrder;
    $materToOrder->idMaterials = $idMaterial;
    $materToOrder->countNeed = $countMaterial;
    $res = $materToOrder->insert();
    if($res != false){
        echo echoUspeh();
    }
    else{
        echo echoNoUspeh();
    }

}
//**удаление маериала к заказу из materialsToOrder
if(isset($_POST['deleteThisMaterialFromOrder'])){
    if(isset($_POST['idMaterialToOrder'])){
        $idMaterialsToOrder = intval($_POST['idMaterialToOrder']);
    }
    $res = MaterialsToOrder::deleteObj($idMaterialsToOrder);
    if($res != false){
//        echo 'удалили материал из заказа';
//        удалим из таблицы эту удаленную строку
        echo echoUspeh();
        echo "<script>$('.deleteThisTr').removeClass('deleteThisTr').remove();</script>";
    }
    else{
        echo echoNoUspeh();
        echo "<script>$('.deleteThisTr').removeClass('deleteThisTr');</script>";
    }
}
//**update количества материала для заказа
if(isset($_POST['updateThisCountMaterialsForOrder'])){
    if(isset($_POST['idMaterialToOrder'])){
        $idMaterialsToOrder = intval($_POST['idMaterialToOrder']);
    }
    if(isset($_POST['countMatNew'])){
        $countMatNew = htmlspecialchars($_POST['countMatNew']);
    }
    $objMaterialsToOrder = MaterialsToOrder::findObjByIdStatic($idMaterialsToOrder);
    $objMaterialsToOrder->countNeed = $countMatNew;
    $res = $objMaterialsToOrder->update();
    if($res != false){
        $objMaterialsToOrder = MaterialsToOrder::findObjByIdStatic($idMaterialsToOrder);
        $priceCountNeed = $objMaterialsToOrder->priceCountNeed;
        $countNeed = $objMaterialsToOrder->countNeed;
        $recomAddCount = $objMaterialsToOrder->recomAddCount;
        $priceRecomNeed = $objMaterialsToOrder->priceRecomNeed;
        $idOrder = $objMaterialsToOrder->idOrder;
        //по idOrder найдем сам заказ, чтобы изменить показ расчетной суммы комплектующих и рекомендуемой суммы комплектующих
        $objOrder = Order::findObjByIdStatic( $idOrder);
        $manufacturingPriceRecom = $objOrder->getManufacturingPriceRecom();
        $manufacturingPriceCount = $objOrder->getManufacturingPriceCount();

        echo echoUspeh();

        //можно сделать функцию для изменения после удачного update строки в таблице всех материало к заказу в модальном окне
//        echo "<script>fUpdatTrWhereUpdateCountMaterial($countNeed,$priceCountNeed,$recomAddCount,$priceRecomNeed);
        echo "<script>
                   var trUpdated = $('.updateCountMaterialToOrder');
                   $(trUpdated).children()[4].textContent = $countNeed;
                   $(trUpdated).children()[5].textContent = $priceCountNeed;
                   $(trUpdated).children()[6].textContent = $recomAddCount;
                   $(trUpdated).children()[7].textContent = $priceRecomNeed;
                   $(trUpdated).removeClass('updateCountMaterialToOrder');
                   $('.manufacturingPriceCount').text('$manufacturingPriceCount');
                   $('.manufacturingPriceRecom').text('$manufacturingPriceRecom');
</script>";
    }
    else{
        echo echoNoUspeh();
    }
}
