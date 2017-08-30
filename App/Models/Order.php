<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 28.06.2017
 * Time: 13:04
 */

namespace App\Models;
//require '../../autoload.php';
use App\Db;
use App\ModelLikeTable;
use App\FastViewTable;
use App\Models\Payment;

class Order extends ModelLikeTable
{
    public $id;
    public $nameOrder;
    public $descriptionOrder;
    public $source;
    public $idClient;
    public $orderPrice;
    public $manufacturingPrice;
    public $isCompleted;
    public $isReady;
    public $isInstall;
    public $dateOfOrdering;
    public $dateOfComplation;
    public $isAllowCalculateCost;
    public $isTrash;
//    private $nameClient;
    private $sumAllPayments;// количество проплаченных денег за заказ не входит в таблицу, а получаем из другой таблицы через запрос
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
            $queryNew= "SELECT  o.id AS idOrder ,o.dateOfOrdering AS dateBegin, o.dateOfComplation AS dateEnd, o.nameOrder, c.name AS nameClient , o.orderPrice,o.isReady, o.isCompleted, SUM( p.sumPayment) AS payment
                  FROM orders o, clients c, payments p
                  WHERE o.idClient = c.id AND idOrder = p.idOrder AND o.id = p.idOrder AND o.isTrash = 0
                  GROUP BY idOrder
                  ORDER BY dateBegin;
                  ";

            $db = new Db();
        $queryOld= "SELECT orders.id AS idOrder , nameOrder, clients.name AS nameClient , orderPrice,isReady, isCompleted,  
                  FROM orders, clients, payments
                  WHERE idClient=clients.id ";
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

//найти все поля закза по переданному id
    public static function findObjByIdForViewOneOrder(int $id){
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
        return Payment::showSumAllPayments($this->id);
    }
}