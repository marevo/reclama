<?php   
require '../../autoload.php';


//if (isset($_POST['submitFromFormOneMaterial'])){
if (isset($_POST['send'])){
//    echo 'yes go ';
    insertNewMaterialToBase();
}
//функция вставки в базу нового материала
function insertNewMaterialToBase(){
    if (isset($_POST['send'])) {

        $matNew = new \App\Models\Material();
        if (isset($_POST['nameMaterial'])) {
            $matNew->name = trim( htmlspecialchars($_POST['nameMaterial']) );
        }
        if (isset($_POST['addCharacteristic'])) {
            $matNew->addCharacteristic = trim( htmlspecialchars($_POST['addCharacteristic']));
        }
        if (isset($_POST['idSupplier'])) {
            $matNew->id_suppliers = intval($_POST['idSupplier']);
        }
        if (isset($_POST['measure'])) {
            $matNew -> measure =trim( htmlspecialchars($_POST['measure']));
        }
        if (isset($_POST['deliveryForm'])) {
            $matNew -> deliveryForm = trim( htmlspecialchars($_POST['deliveryForm']));
        }
        if(isset($_POST['priceForMeasure'])){
            $matNew -> priceForMeasure = trim(htmlspecialchars($_POST['priceForMeasure']));
        }
        //вставим новый заказ в базу
        $resInsert = $matNew -> insert();
        
        if($resInsert != false){
            echo "<script> fUspehAll('материал добавлен');</script>";
        }
        else{
            echo "<script>fNoUspehAll('материал не добавлен(');</script>";
        }
//        if (isset($_POST['submitFromFormOneOrder']))
//            foreach ($orNew as $k => $value) {
//                echo "<br/>$k--- $value";
//            }
    }
}

//поиск поставщика по подобию имени  и выгрузка их в селект выбора поставщиков formAddNewMaterilsToBase.php ['name = idSupplier']
if(isset($_POST['searchSuppliersLikeName'])){
//    $likeName = htmlspecialchars($_POST['likeName']);
//    echo "<option value=\"0\">пришел запрос на поиск поставщика по $likeName </option>";
//    die();
    if(isset($_POST['likeName'])){
        $likeName = htmlspecialchars($_POST['likeName']);
//        \App\ModelLikeTable::showUspeh("пришел запрос на поиск по имени $likeName");
//        $suppliersSearcLikeName = \App\Models\Supplier::searchAllForLikeName($likeName);
        $suppliersSearcLikeName = \App\Models\Supplier::searchObjectsLikeNameOrLikeAddCharacteristic($likeName);
        if($suppliersSearcLikeName){
            $optionSearchingSuppliers= "<option value=\"0\">выберите поставщика</option>";
            foreach ($suppliersSearcLikeName as $rowItem){
                $optionSearchingSuppliers .= "<option data-id = '$rowItem->id' value='$rowItem->id'>$rowItem->name</option>";
            }
            echo "$optionSearchingSuppliers";
        }
        else{
            $optionSearchingSuppliers= "<option value=\"0\">такого нет (: </option>";
            \App\ModelLikeTable::showNoUspeh("не нашли с именем $likeName (:");

            echo "$optionSearchingSuppliers";
        }
    }
}

