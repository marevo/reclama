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
	
   public static function getByBirthday(string $start, string $end){
//        echo '<br>вызов из класса '.get_called_class().'<br>';
//        echo '<br>вызов данных из таблицы '.static::TABLE .'<br>';
//        echo '<br>вызов из класса '.static::class.'<br>';
        $db = new Db();
        //позднее связывание для вызова с параметрами класса наследника
        //это позволит вернуть результат сразу в виде массива типа класса наследника

        $res = $db->query("SELECT * FROM ".static::TABLE ." WHERE birthday BETWEEN '".$start."' AND '".$end."' ;",static::class );
//        var_dump('<br>$res = '.$res.'<br>');
        return $res;
   }
}