<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 28.06.2017
 * Time: 13:25
 */

namespace App\Models;
use App\Db;
use App\ModelLikeTable;
use App\FastViewTable;

class MaterialsToOrder extends ModelLikeTable 
{
    public $id;
    public $idOrder;
    public $idMaterials;
    public $countNeed;
    public $priceCountNeed;
    public $recomAddCount;
    public $priceRecomNeed;

    const TABLE = 'materialsToOrder';
    const NAME_ID ='id';

    public function isNew()
    {
        // TODO: Implement isNew() method.
        if(empty($this->id) || is_null($this->id)){
            return true;
        }
        else{
            return false;
        }
    }
    //получить все материалы к заказу с idOrderr = $idOrder
    public static function getAllMaterialsToOrder($idOrder){
        $query = "SELECT * FROM ".static::TABLE." WHERE  idOrder  = '$idOrder';";
//        var_dump( "пришел запрос на материалы к заказу : ".$query );
        $db = new Db();
        $res = $db->query($query, static::class);
        if($res != false)
            return $res;
        else
            return false;
    }
    //получить все материалы к заказу с idOrderr = $idOrder
    public static function getAllMaterialstoOrderWithNameMaterials($idOrder){
        $query = "SELECT * FROM ".static::TABLE." WHERE idOrder  = '$idOrder';";
        $db = new Db();
        $res = $db->query($query, static::class);
        if($res != false)
            return $res;
        else
            return false;
    }
//    нахождение всех id материалов что есть в заказе
    public static function ifExistThisMaterialInAnyOneOrder($idMaterial){
        $query = "SELECT  idOrder FROM ".static ::TABLE." WHERE idMaterials = '$idMaterial';";
//        echo " <br/> $query    ";
        $db = new Db();
        $sth = $db->get_dbh()->prepare($query);
        $res = $sth->execute();
        if(false != $res)
            return $sth->fetchAll();
        else
            return false;
    }

    public static function ifExistThisSupplierInAnyMaterilsToOrder($idSupplier){
// для проверки есть ли материалы поставщика хотябы в одном заказе     SELECT  idOrder FROM materialsToOrder WHERE idMaterials IN (SELECT id FROM materials WHERE id_suppliers = '1');

        $query = "SELECT  idOrder FROM ".static ::TABLE." WHERE idMaterials IN (SELECT id FROM materials WHERE id_suppliers = '$idSupplier') ;";
//        echo " <br/> $query    ";
        $db = new Db();
        $sth = $db->get_dbh()->prepare($query);
        $res = $sth->execute();
        if(false != $res)
            return $sth->fetchAll();
        else
            return false;
    }
}