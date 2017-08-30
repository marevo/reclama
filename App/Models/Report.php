<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 26.06.2017
 * Time: 17:00
 */

namespace App\Models;
use App\Db;
use App\FastViewTable;
use App\ModelLikeTable;


class Client extends ModelLikeTable
{
    public $id;
    public $name;//название Клиента
    public $phone0;
    public $phone1;
    public $email0;
    public $contactPerson;//имя контактного лица
    public $address;

    use FastViewTable;
    
    const TABLE = 'clients';
    const NAME_ID ='id';
//    static $table = 'clients';
    public function isNew()
    {
        // TODO: Implement isNew() method.
        if(empty($this->id) || is_null($this->id) ){
            return true;
        }
        else{
            return false;
        }
    }

    public static function getAllOrderByName(){
        $db = new Db();
        $query = "SELECT * FROM ".self::TABLE." ORDER BY nameOrder ; ";
//        var_dump('<br>обработаем запрос : '.$query.' в функции getAllSuppliers of class '.self.'<br>');

        $res = $db->query($query, self::class );
        return $res;
    }
   
}