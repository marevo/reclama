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


