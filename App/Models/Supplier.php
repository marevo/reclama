<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 23.06.2017
 * Time: 13:01
 */

namespace App\Models;
use App\Db;
use App\ModelLikeTable;

class Supplier extends ModelLikeTable
{
    public $id;
    public $name;
    public $addCharacteristic;
    public $phone0;
    public $email0;
    public $contactPerson;
    public $address;
    public $deliveryDay;
    public $site;

    const TABLE = 'suppliers';
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
   
    public static function getAllSuppliers(){
        $db = new Db();
        $query = "SELECT * FROM ".self::TABLE." ORDER BY name ; ";
//        var_dump('<br>обработаем запрос : '.$query.' в функции getAllSuppliers of class '.self.'<br>');

        $res = $db->query($query, self::class );
        //при вызове всех поставщиков результат есть
//        var_dump($res);
        return $res;
    }
}