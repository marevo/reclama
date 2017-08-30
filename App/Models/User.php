<?php
namespace App\Models;
use App\Db;
//use App\FastViewTable;
use App\ModelLikeTable;


class User extends ModelLikeTable
{
    public $login;
    public $password;//название Клиента
    public $session;
    public $updated;

 //   use FastViewTable;
    
    const TABLE = 'users';
    const NAME_ID ='login';
//    static $table = 'clients';
    public function isNew()
    {
        // TODO: Implement isNew() method.
        if(empty($this->login) || is_null($this->login) ){
            return true;
        }
        else{
            return false;
        }
    }

    public static function getCurrentUserBySession(string $session){
        $db = new Db();
        $query = "SELECT * FROM ".self::TABLE." WHERE session = '".$session."' ; ";

        $res = $db->query($query, self::class );
        return $res;
    }
    public static function getCurrentUserByLogin(string $login)
	{
        $db = new Db();
        $query = "SELECT * FROM ".self::TABLE." WHERE login = '".$login."' ; ";

        $res = $db->query($query, self::class );
        return $res;
//         return $query;
	}
	public static function createSession(string $login)
	{
		$db = new Db();
		$values = [];
		$values [':session']=session_id();
		$values [':updated']=time();
        $query = "UPDATE ".self::TABLE." SET session = :session, updated = :updated WHERE login = '".$login."' ; ";
		$res = $db->execute($query, $values);
        return $res;
	}
}