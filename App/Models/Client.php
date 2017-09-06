<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 26.06.2017
 * Time: 17:00
 */

namespace App\Models;
use App\Db;
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
        $query = "SELECT * FROM ".self::TABLE." ORDER BY name ; ";
//        var_dump('<br>обработаем запрос : '.$query.' в функции getAllSuppliers of class '.self.'<br>');

        $res = $db->query($query, self::class );
        return $res;
    }
    //метод  статический существуют ли заказы для клиента с $idClient  вернет false если заказов нет у этого клиента 
    public static function ifExistAnyOrderForClient(int $idClient){
        $db = new Db();
        $query = "SELECT * FROM orders WHERE idClient =  $idClient ; ";
//        var_dump('<br>обработаем запрос : '.$query.' в функции getAllSuppliers of class '.self.'<br>');
        $res = $db->query($query, self::class );
        return $res;
    }

    //метод существуют ли заказы для this клиента вернет false если заказов нет у этого клиента
    public  function ifExistAnyOrderForThisClient(){
        $db = new Db();
        $query = "SELECT * FROM orders WHERE idClient =  $this->id ; ";
//        var_dump('<br>обработаем запрос : '.$query.' в функции getAllSuppliers of class '.self.'<br>');
        $res = $db->query($query, self::class );
        return $res;
    }
    //метод найти всех клиентов по подобию имени или контакта    
    public static function searchAllForLikeNameOrLikeContactPerson(string $likeNamLikeContact){
        $db = new Db();
        $query = "SELECT * FROM ".self::TABLE." WHERE name LIKE '%$likeNamLikeContact%' OR contactPerson LIKE '%$likeNamLikeContact%' ; ";
//        var_dump('<br>обработаем запрос : '.$query.' в функции getAllSuppliers of class '.self.'<br>');
        $res = $db->query($query, self::class );
        return $res;
    }
}