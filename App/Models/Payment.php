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
    public static function getSumAllPaymentsForOrder($idOrder){
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
    // количество проплат по заказу idOrder
    public static function getCountPaymentsForOrder($idOrder){
        $query = "SELECT COUNT(sumPayment) AS countSumPayments FROM payments WHERE idOrder = $idOrder ;";
        $db = new Db();
        $sth = $db->get_dbh()->prepare($query);
        $res = $sth->execute();
        if($res){
            return $sth->fetchAll()[0][countSumPayments];
        }else return 0;
        
        
    }
    //метод  выбрать все оплаты по $idOrder из таблицы
    public static function getAllPaymentsForOrder($idOrder){
        $db = new Db();
        $query = "SELECT * FROM ". self::TABLE ." WHERE idOrder =  $idOrder  ORDER BY date DESC; ";
//        $query = "SELECT * FROM ". self::TABLE ." WHERE idOrder =  $idOrder ORDER BY `date` ; ";
        $res = $db->query($query, self::class );
        return $res;
    }
//метод из таблицы
    public  static function getAllPaymentsOrderByDateDesc(){
        $query = "SELECT p.id AS id, c.id AS idClient , c.name AS nameClient, o.id AS idOrder , o.name AS nameOrder , p.sumPayment AS sumPayment,
                  p.date AS datePayment   FROM payments p LEFT OUTER JOIN clients c ON p.idClient = c.id 
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
                  SUM(p.sumPayment) AS sumAllPaymentOrder , COUNT(p.sumPayment) AS countPayments , o.orderPrice AS orderPrice
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
//    найти все оплаты по клиенту с именем подобным $likeName (поиск только по имени)
// 1 - по блок-схеме поиска платежей
    public static function getPaymentsClientsOrdersSumPaymentsCountPaymantsLikeName($likeName){
        $query ="SELECT c.id AS idClient , c.name AS nameClient, o.id AS idOrder , o.name AS nameOrder ,
                   SUM(p.sumPayment) AS sumAllPaymentOrder , COUNT(p.sumPayment) AS countPayments , o.orderPrice AS orderPrice
                   FROM payments p
                   LEFT OUTER JOIN clients c ON p.idClient = c.id 
                   LEFT OUTER JOIN orders o ON p.idOrder = o.id
                   WHERE c.id IN(SELECT id FROM clients AS c  WHERE name LIKE '%$likeName%')
                   GROUP BY c.name , o.name" ;
        $db = new Db();
        $sth = $db->get_dbh()->prepare($query);
        $res = $sth->execute();
        if($res){
            return $sth->fetchAll();
        }else{
            return false;
        }
    }
//найти все оплаты по клиенту с именем подобным $likeName и между датами "от" и "до"
    public static function getPaymentsClientsOrdersSumPaymentsCountPaymantsLikeNameDateFromDateTo($likeName, $dateFrom, $dateTo){
        $query ="SELECT c.id AS idClient , c.name AS nameClient, o.id AS idOrder , o.name AS nameOrder ,
                   SUM(p.sumPayment) AS sumAllPaymentOrder , COUNT(p.sumPayment) AS countPayments , o.orderPrice AS orderPrice
                   FROM payments p
                   LEFT OUTER JOIN clients c ON p.idClient = c.id
                   LEFT OUTER JOIN orders o ON p.idOrder = o.id
                   WHERE c.id IN(SELECT id FROM clients AS c  WHERE name LIKE '%$likeName%') AND p.date BETWEEN '$dateFrom' AND '$dateTo'
                   GROUP BY c.name , o.name
                   " ;
        $db = new Db();
        $sth = $db->get_dbh()->prepare($query);
        $res = $sth->execute();
        if($res){
            return $sth->fetchAll();
        }else{
            return false;
        }
    }

//найти все оплаты между датами "от" и "до"
//по блок-схеме 3 
    public static function getPaymentsClientsOrdersSumPaymentsCountPaymantsDateFromDateTo($dateFrom, $dateTo){
        $query ="SELECT c.id AS idClient , c.name AS nameClient, o.id AS idOrder , o.name AS nameOrder ,
                   SUM(p.sumPayment) AS sumAllPaymentOrder , COUNT(p.sumPayment) AS countPayments , o.orderPrice AS orderPrice
                   FROM payments p
                   LEFT OUTER JOIN clients c ON p.idClient = c.id
                   LEFT OUTER JOIN orders o ON p.idOrder = o.id
                   WHERE  p.date  BETWEEN '$dateFrom' AND '$dateTo'
                   GROUP BY c.name , o.name" ;
        $db = new Db();
        $sth = $db->get_dbh()->prepare($query);
        $res = $sth->execute();
        if($res){
            return $sth->fetchAll();
        }else{
            return false;
        }
    }

//найти все оптаты по всем клиентам до даты $dateTo
    public static function getPaymentsClientsOrdersSumPaymentsCountPaymantsDateLessDateTo($dateTo){
        $query ="SELECT c.id AS idClient , c.name AS nameClient, o.id AS idOrder , o.name AS nameOrder ,
                   SUM(p.sumPayment) AS sumAllPaymentOrder , COUNT(p.sumPayment) AS countPayments , o.orderPrice AS orderPrice
                   FROM payments p
                   LEFT OUTER JOIN clients c ON p.idClient = c.id
                   LEFT OUTER JOIN orders o ON p.idOrder = o.id
                   WHERE  p.date <= '$dateTo'
                   GROUP BY c.name , o.name" ;
        $db = new Db();
        $sth = $db->get_dbh()->prepare($query);
        $res = $sth->execute();
        if($res){
            return $sth->fetchAll();
        }else{
            return false;
        }
    }

    //найти все оптаты  до даты $dateTo  и по подобию имени
    //6 на блок схеме поиска платежей
    public static function getPaymentsClientsOrdersSumPaymentsCountPaymantsDateLessDateToAndLikeName($dateTo, $likeName){
        $query ="SELECT c.id AS idClient , c.name AS nameClient, o.id AS idOrder , o.name AS nameOrder ,
                   SUM(p.sumPayment) AS sumAllPaymentOrder , COUNT(p.sumPayment) AS countPayments , o.orderPrice AS orderPrice
                   FROM payments p
                   LEFT OUTER JOIN clients c ON p.idClient = c.id
                   LEFT OUTER JOIN orders o ON p.idOrder = o.id
                   WHERE c.id IN(SELECT id FROM clients AS c  WHERE name LIKE '%$likeName%') AND p.date <= '$dateTo'
                   GROUP BY c.name , o.name" ;
        $db = new Db();
        $sth = $db->get_dbh()->prepare($query);
        $res = $sth->execute();
        if($res){
            return $sth->fetchAll();
        }else{
            return false;
        }
    }

    //найти все оптаты по всем клиентам от даты $dateFrom
    // 5 пункт в блок схеме
    public static function getPaymentsClientsOrdersSumPaymentsCountPaymantsDateMoreDateFrom($dateFrom){
        $query ="SELECT c.id AS idClient , c.name AS nameClient, o.id AS idOrder , o.name AS nameOrder ,
                   SUM(p.sumPayment) AS sumAllPaymentOrder , COUNT(p.sumPayment) AS countPayments , o.orderPrice AS orderPrice
                   FROM payments p
                   LEFT OUTER JOIN clients c ON p.idClient = c.id
                   LEFT OUTER JOIN orders o ON p.idOrder = o.id
                   WHERE   '$dateFrom' <= p.date 
                   GROUP BY c.name , o.name" ;
        $db = new Db();
        $sth = $db->get_dbh()->prepare($query);
        $res = $sth->execute();
        if($res){
            return $sth->fetchAll();
        }else{
            return false;
        }
    }

    //найти все оптаты по клиентам (только по именам подобным) и от даты $dateFrom
    // 4 - на блок-схеме
    public static function getPaymentsClientsOrdersSumPaymentsCountPaymantsDateMoreDateFromAndLikeName($dateFrom, $likeName){
        $query ="SELECT c.id AS idClient , c.name AS nameClient, o.id AS idOrder , o.name AS nameOrder ,
                   SUM(p.sumPayment) AS sumAllPaymentOrder , COUNT(p.sumPayment) AS countPayments , o.orderPrice AS orderPrice
                   FROM payments p
                   LEFT OUTER JOIN clients c ON p.idClient = c.id
                   LEFT OUTER JOIN orders o ON p.idOrder = o.id
                   WHERE c.id IN(SELECT id FROM clients AS c  WHERE name LIKE '%$likeName%') AND '$dateFrom' <= p.date 
                   GROUP BY c.name , o.name" ;
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