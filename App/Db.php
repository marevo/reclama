<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 09.06.2017
 * Time: 16:32
 */
namespace App;
class Db
{
    /**
     * @var \PDO
     */
    protected $dbh;//соедининие
    protected $dns;//dns (host+dbname)
    protected $user;//user
    protected $pass;//password
    public function get_dbh(){
        return $this->dbh;
    }
    //1 вопрос надо ли создавать кроме $dbh  еще 3 другие переменные или достаточно в конструкторе своих внутренних переменных
    public function __construct(string $user='root', string $pass='')
    {
        //var_dump('<br>path, где лежит Db.php :  '.__DIR__.'<br>');
        $config = include (__DIR__.'/../config.php');
       // var_dump('<br>path, где лежит config.php :  '.__DIR__.'/../config.php'.'<br>');
       // var_dump($config);//see array from file config.php
       // var_dump('<br>вывод из функции __constructor str 26 переменная $config<br>'.$config);
        //если передали параметры в метод, то вызов будет от имени переданных user & password
        //иначе по умолчанию 'user'=> 'root', 'password'=> ''
        $this->user = $user;
        $this->pass = $pass;

        $this->dns = 'mysql:host='.$config['host'].';dbname='.$config['dbname'];
        $this->dbh = new \PDO($this->dns,$this->user,$this->pass);
    }

    /**
     * @param string $sqlString
     * @param array $params
     * @return bool rez ot query (true or false)
     */
    public function execute(string  $sqlString, $params = []){
        //для операций вставки, апдейта, удаления вернет удачно или не удачно
//        echo ('запрос в базу '.$sqlString);
//        echo ('параметры запроса в виде массива  на строку ниже <br> '.$params);
//        var_dump($params);
//        die();
        $sth = $this-> dbh->prepare($sqlString);
        $res = $sth->execute($params);//вернет true or false
        return $res;
    }

    /**
     * @param string $sqlString
     * @param $class
     * @return $class[] in true rez or fasle in false query
     */
    public function query(string $sqlString, $class){
//        для получения данных по запросу
//        var_dump('<br>зашли в function query in Db.php<br>');
//        var_dump('в function query in Db.php пришел запрос : '.$sqlString);
        $sth = $this-> dbh -> prepare($sqlString);
//        var_dump('<br>выведем из DB.php $sth: '.$sth); //как посмотреть $sth
        $res = $sth->execute();//вернет true or false
        if(false != $res) {
//            var_dump('<br>должен быть результат вызова в  function query in Db.php<br>');
            return $sth->fetchAll(\PDO::FETCH_CLASS, $class);
        }
        else{
//            var_dump('<br>последняя строка в результата нет !!! function query in Db.php<br>');
            return false;
        }

    }
}