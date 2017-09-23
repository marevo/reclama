<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 28.06.2017
 * Time: 13:04
 */

namespace App\Models;
use App\Db;
use App\ModelLikeTable;
use App\FastViewTable;

class Order extends ModelLikeTable
{
    public $id;
    public $name;
    public $descriptionOrder;
    public $source;
    public $idClient;
    public $orderPrice;//цена заказа
    public $manufacturingPrice;//цена комплектующих
    public $isCompleted;
    public $isReady;//состояние готовности заказа
                   //степень готовности заказа
                   // 0-новый( надо еще посчитать стоимость и связаться с заказчиком),
                    // 1-закрыт успешно,
                    // 2-закрыт неуспешно,
                      // 3-запущен
    public $isInstall;
    public $dateOfOrdering;
    public $dateOfComplation;
    public $isAllowCalculateCost;//разрешать добавлсять материалы к заказу и пересчитывать цены автоматически если они поменялись в таблице материалов
    public $isTrash;

//    private $sumAllPayments;// количество проплаченных денег за заказ не входит в таблицу, а получаем из другой таблицы через запрос
    const TABLE = 'orders';
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
//функция выборки 1)id заказа,2) названия заказа,3) названия клиента, 4)цена заказа, 5)степень готовности-0-новый, 1-завершен-успешно, 2-завершен неуспешно,
//6) sum(select sumPayment from payments) 7) можно ли менять стоимость комплектующих, если удален (isTrash=1) - то не показываем, а если не удален isTrash=0 тогда показываем
        public static function selectForView( ){
            //запрос заказов, клиентов, суммы оплаты с группировкой заказам
            $queryOld= "SELECT  o.id AS idOrder ,o.dateOfOrdering AS dateBegin, o.dateOfComplation AS dateEnd, o.name, c.name AS nameClient , 
                        o.orderPrice,o.isReady, o.isCompleted, SUM( p.sumPayment) AS payment
                  FROM orders o, clients c, payments p
                  WHERE o.idClient = c.id AND o.id = p.idOrder AND o.isTrash = 0
                  GROUP BY idOrder , nameClient , o.name
                  ORDER BY dateBegin DESC ,nameClient, o.name ;
                  ";

            $db = new Db();
        $queryNew= "SELECT o.id AS idOrder , o.dateOfOrdering AS dateBegin, o.dateOfComplation AS dateEnd, o.name ,  c.name AS nameClient ,
       o.orderPrice,o.isReady, o.isCompleted, o.isTrash , SUM(p.sumPayment) AS payment
FROM orders AS o LEFT OUTER JOIN  clients AS c ON o.idClient = c.id LEFT OUTER JOIN payments AS p ON o.id = p.idOrder
WHERE o.isTrash = 0
GROUP BY idOrder, nameClient, o.name
ORDER BY dateBegin DESC ,nameClient, o.name ;
 ";
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
//** найти все удаленные заказы (в корзине) */
    /**
     * @return mixed
     */
    public static function getTrashedOrders()
    {
        $queryFindTrashedOrders= "SELECT  o.id AS idOrder ,o.dateOfOrdering AS dateBegin, o.dateOfComplation AS dateEnd, o.name, c.name AS nameClient , o.orderPrice,o.isReady, o.isCompleted, SUM( p.sumPayment) AS payment
                  FROM orders o, clients c, payments p
                  WHERE o.idClient = c.id AND o.id = p.idOrder AND o.isTrash = 1
                  GROUP BY idOrder
                  ORDER BY dateBegin DESC ;
                  ";
        $db = new Db();
        $sth = $db->get_dbh()->prepare($queryFindTrashedOrders);
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

//**найти заказы по подобию названия (покажет даже те что в корзине)
public static function getOrdersLikeNameClient( $likeName){

    $queryFindOrderLikeName = "SELECT o.id AS idOrder , o.dateOfOrdering AS dateBegin, o.dateOfComplation AS dateEnd,
                                  o.name ,  c.name AS nameClient ,o.orderPrice,o.isReady, o.isCompleted,  SUM(p.sumPayment) AS payment
                                  FROM orders AS o LEFT OUTER JOIN  clients AS c ON o.idClient = c.id LEFT OUTER JOIN payments AS p ON o.id = p.idOrder
                                  WHERE c.name  LIKE '%$likeName%'
                                  GROUP BY idOrder, nameClient, o.name
                                  ORDER BY dateBegin DESC ,nameClient, o.name ;";

    $queryFindOrderLikeNameOld= "SELECT  o.id AS idOrder ,o.dateOfOrdering AS dateBegin, o.dateOfComplation AS dateEnd,
                              o.name, c.name AS nameClient , o.orderPrice,o.isReady, o.isCompleted, SUM( p.sumPayment) AS payment
                  FROM orders o, clients c, payments p
                  WHERE o.idClient = c.id AND o.id = p.idOrder AND o.isTrash = 0 AND c.name  LIKE '%$likeName%'
                  GROUP BY idOrder
                  ORDER BY dateBegin DESC ;
                  ";
//    echo $queryFindOrderLikeName;
//    die();
    $db = new Db();
    $sth = $db->get_dbh()->prepare($queryFindOrderLikeName);
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
    public static function getOrdersLikeName( $likeName){
        $queryFindOrderLikeName = "SELECT o.id AS idOrder , o.dateOfOrdering AS dateBegin, o.dateOfComplation AS dateEnd,
                                  o.name ,  c.name AS nameClient ,o.orderPrice,o.isReady, o.isCompleted,  SUM(p.sumPayment) AS payment
                                  FROM orders AS o LEFT OUTER JOIN  clients AS c ON o.idClient = c.id LEFT OUTER JOIN payments AS p ON o.id = p.idOrder
                                  WHERE o.name  LIKE '%$likeName%'
                                  GROUP BY idOrder, nameClient, o.name
                                  ORDER BY dateBegin DESC ,nameClient, o.name ;";

        $queryFindOrderLikeNameOld= "SELECT  o.id AS idOrder ,o.dateOfOrdering AS dateBegin, o.dateOfComplation AS dateEnd, o.name, c.name AS nameClient , o.orderPrice,o.isReady, o.isCompleted, SUM( p.sumPayment) AS payment
                  FROM orders o, clients c, payments p
                  WHERE o.idClient = c.id AND o.id = p.idOrder AND o.isTrash = 0 AND o.name  LIKE '%$likeName%'
                  GROUP BY idOrder
                  ORDER BY dateBegin DESC ;
                  ";
//    echo $queryFindOrderLikeName;
//    die();
        $db = new Db();
        $sth = $db->get_dbh()->prepare($queryFindOrderLikeName);
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

    // найти все заказы в виде объектов по подобию в названии заказа или названи клиента
    /**
     * @param string  $likeNameClient
     * @param string $likeNameOrder
     * @return bool|mixed
     */
    public static function getOrdersLikeNameOrderOrLikeNameClient(  $likeNameClient = NULL ,  $likeNameOrder = NULL){
        $queryFindOrderLikeName ="";
//        если передали 2 параметра
        if($likeNameClient && $likeNameOrder){
            $queryFindOrderLikeName = "SELECT o.id AS idOrder, o.name AS nameOrder, c.id AS idClient, c.name AS nameClient, o.isReady AS orderIsReady
                                   FROM orders AS o INNER JOIN  clients AS c ON o.idClient = c.id 
                                   WHERE ( o.name  LIKE '%$likeNameOrder%' AND c.name LIKE '%$likeNameClient%' ) AND ( o.isReady = 0 OR o.isReady = 3 )
                                   ORDER BY c.name, o.name
                                  ";
        }else{
            if($likeNameClient || $likeNameOrder){
                //            если один парметр
                if($likeNameOrder ) {
                    $queryFindOrderLikeName = "SELECT o.id AS idOrder, o.name AS nameOrder, c.id AS idClient, c.name AS nameClient, o.isReady AS orderIsReady
                                   FROM orders AS o INNER JOIN  clients AS c ON o.idClient = c.id 
                                   WHERE ( o.name  LIKE '%$likeNameOrder%' OR c.name LIKE '%$likeNameOrder%' ) AND ( o.isReady = 0 OR o.isReady = 3 )
                                   ORDER BY c.name, o.name
                                  ";
                }else{
                    $queryFindOrderLikeName = "SELECT o.id AS idOrder, o.name AS nameOrder, c.id AS idClient, c.name AS nameClient, o.isReady AS orderIsReady
                                   FROM orders AS o INNER JOIN  clients AS c ON o.idClient = c.id 
                                   WHERE ( o.name  LIKE '%$likeNameClient%' OR c.name LIKE '%$likeNameClient%' ) AND ( o.isReady = 0 OR o.isReady = 3 )
                                   ORDER BY c.name, o.name
                                  ";

                }


//                if($likeNameClient){
//                    $queryFindOrderLikeName = "SELECT o.id AS idOrder, o.name AS nameOrder, c.id AS idClient, c.name AS nameClient, o.isReady AS orderIsReady
//                                   FROM orders AS o INNER JOIN  clients AS c ON o.idClient = c.id
//                                   WHERE  c.name LIKE '%$likeNameClient%'  AND ( o.isReady = 0 OR o.isReady = 3 )
//                                   ORDER BY c.name, o.name
//                                  ";
//                }else{
////                    передали $likeNameOrder
//                    $queryFindOrderLikeName = "SELECT o.id AS idOrder, o.name AS nameOrder, c.id AS idClient, c.name AS nameClient, o.isReady AS orderIsReady
//                                   FROM orders AS o INNER JOIN  clients AS c ON o.idClient = c.id
//                                   WHERE ( o.name  LIKE '%$likeNameOrder%' AND c.name LIKE '%$likeNameClient%' ) AND ( o.isReady = 0 OR o.isReady = 3 )
//                                   ORDER BY c.name, o.name
//                                  ";
//                }
            }
        }
//есть строка запроса
        if($queryFindOrderLikeName){
            //    echo $queryFindOrderLikeName;
//    die();
            $db = new Db();
            $sth = $db->get_dbh()->prepare($queryFindOrderLikeName);
            $res = $sth->execute();
            if(false != $res) {
//            var_dump('<br>должен быть результат вызова в  function query in Db.php<br>');
//            return $sth->fetchObject('Order');
                return $sth->fetchAll();
            }
            else{
//            var_dump('<br>последняя строка в результата нет !!! function query in Db.php<br>');
                return false;
            }
        }
        
        return false;
    }


//найти все поля закза по переданному id
    public static function findObjByIdForViewOneOrder($id){
//        echo '<br>вызов из класса Order  функция findObjByIdForViewOneOrder получили результат не false<br>';
        $res = self::findObjByIdStatic($id);
//        var_dump($res);
        return $res;
    }
    public function getNameClient(){
        $cl = Client::findObjByIdStatic($this->idClient);
//        var_dump("в классе заказ по idClent нашли клиента ".$cl->name);
        return $cl->name;
    }
    public  function getSumAllPayments(){
        return Payment::getSumAllPaymentsForOrder($this->id);
    }
    
    //метод найдет заказ по названию если он есть такой
    //*если есть заказ с таким именем вернет obj Order заказа
    //*если нет то вернет false
    public static function isAllowNameOrder ( $name){
        $query = "SELECT * FROM orders WHERE orders.name = '$name';";
//        echo 'пришел запрос на проверку названия заказа '.$query;
        $db = new  Db();
        $res = $db ->query($query,static::class);
        if($res != false)    
           return $res[0];
        else return false;
    }
    //запрос стоимости всех комплектующих по расчету к заказу
    public function getManufacturingPriceCount(){
        $query = "SELECT SUM(priceCountNeed) FROM materialsToOrder WHERE idOrder = $this->id ;";
        $db = new Db();
        $sth = $db->get_dbh() -> prepare($query);
        $res = $sth->execute();
        if($res != false){
            return $sth->fetchAll()[0][0];
        }
        return 0;
    }

    //запрос стоимости всех комплектующих по рекомендации системы( должен быть больше чем по расчету) к заказу
    public function getManufacturingPriceRecom(){
        $query = "SELECT SUM(priceRecomNeed) FROM materialsToOrder WHERE idOrder = $this->id ;";
        $db = new Db();
        $sth = $db->get_dbh() -> prepare($query);
        $res = $sth->execute();
        if($res != false){
            return $sth->fetchAll()[0][0];
        }
        return 0;
    }
    
}