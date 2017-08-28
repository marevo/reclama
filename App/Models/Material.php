<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 26.06.2017
 * Time: 21:40
 */

namespace App\Models;
use App\Db;
use App\ModelLikeTable;

class Material extends ModelLikeTable
{
    public $id;
    public $name;
    public $addCharacteristic;
    public $measure;
    public $deliveryForm;
    public $priceForMeasure;
    public $id_suppliers;

    const TABLE = 'materials';
    const NAME_ID ='id';
//    public $nameSupplier;
    public function isNew()
    {
        // TODO: Implement isNew() method.
        if(empty($this->id) || is_null( $this->id)){
            return true;
        }
        else{
            return false;
        }
    }

    public static function selectForView( ){
        //запрос заказов, клиентов, суммы оплаты с группировкой заказам
        $queryNew = "SELECT  m.id  , m.name, m.addCharacteristic, m.measure, m.deliveryForm, m.priceForMeasure, m.id_suppliers as idSupplier, s.name AS nameSupplier 
                     FROM materials AS m, suppliers AS s
                     WHERE m.id_suppliers = s.id 
                     
                     ORDER BY m.name ;
                  ";
//        GROUP BY m.name
        $db = new Db();
        $sth = $db->get_dbh()->prepare($queryNew);
        $res = $sth->execute();
        if(false != $res) {
//            var_dump('<br>должен быть результат вызова в  function query in Db.php<br>');
            return $sth->fetchAll();
        }
        else{
//            var_dump('<br>последняя строка в результата нет !!! function query in Db.php<br>');
            return false;
        }
    }

}