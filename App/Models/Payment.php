<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 09.07.2017
 * Time: 22:57
 */

namespace App\Models;
use App\Db;
use App\ModelLikeTable;
//require ('../../autoload.php');//нужно ли здесь этот autoload ?

class Payment extends ModelLikeTable 
{
    public $id;
    public $idOrder;
    public $idClient;
    public $sumPayment;
    public $date;
    const TABLE = 'payments';
    const NAME_ID ='id';

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
    public static function showSumAllPayments(int $idOrder){
        $query = "SELECT SUM(sumPayment) AS sumAllPayments FROM payments WHERE idOrder= $idOrder;";
        $db = new Db();
        $sth = $db->get_dbh()->prepare($query);
        $res = $sth->execute();
        if(false != $res) {
            return $sth->fetchAll()[0][sumAllPayments];
        }
        else{
            return 0;
        }
    }

}