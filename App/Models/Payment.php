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

//    public function  __construct(int $id=NULL, int $idOrder=NULL, int $idClient=NULL, float $sumPayment=NULL, string $date=NULL){
//        $this->id = $id;
//        $this->idOrder = $idOrder;
//        $this->idClient = $idClient;
//        $this->sumPayment = $sumPayment;
//        $this->date = $date;
//    }
    
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
    //метод  выбрать все оплаты по $idOrder 
    public static function getAllPaymentsForOrder(int $idOrder){
        $db = new Db();
        $query = "SELECT * FROM ". self::TABLE ." WHERE idOrder =  $idOrder ; ";
//        $query = "SELECT * FROM ". self::TABLE ." WHERE idOrder =  $idOrder ORDER BY `date` ; ";
        $res = $db->query($query, self::class );
        return $res;
    }

    public  static function getAllPaymentsOrderByDateDesc(){
        $query = "SELECT p.id AS id, c.id AS idClient , c.name AS nameClient, o.id AS idOrder , o.name AS nameOrder , p.sumPayment AS sumPayment,
                  p.date AS datePayment  FROM payments p LEFT OUTER JOIN clients c ON p.idClient = c.id 
                  LEFT OUTER JOIN orders o ON p.idOrder = o.id 
                  GROUP BY idOrder, id, sumPayment 
                  ORDER BY datePayment DESC ;";
        $db = new Db();
        $sth = $db->get_dbh()->prepare($query);
        $res = $sth->execute();
        if($res){
            return $sth->fetchAll();
        }
        else return false ;
        
    }
    //запрос клиента (idClient, nameClient)  заказа (idOrder,nameOrder), сумма всех оплат по этому заказу количество всех оплат по этому заказу 
    public static function getClientsOrdersSumPaymentsCountPaymants(){
        $query ="SELECT c.id AS idClient , c.name AS nameClient, o.id AS idOrder , o.name AS nameOrder ,
                  SUM(p.sumPayment) AS sumAllPaymentOrder , COUNT(p.sumPayment) AS countPayments
                  FROM payments p 
                  LEFT OUTER JOIN clients c ON p.idClient = c.id 
                  LEFT OUTER JOIN orders o ON p.idOrder = o.id 
                  GROUP BY idOrder
                  ORDER BY nameClient ;" ;
        $db = new Db();
        $sth = $db->get_dbh()->prepare($query);
        $res = $sth->execute();
        if($res){
            return $sth->fetchAll();
        }else{
            return false;
        }
    }

}