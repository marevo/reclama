<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 09.06.2017
 * Time: 17:58
 */
namespace App;

abstract class ModelLikeTable
{
    //переменные будут переопределены в классах наследниках
    const TABLE = '';//назание таблицы
    const NAME_ID ='';//название поля
    use FastViewTable;//используем код в трейте FastViewTable он будет доступен всем наследникам
    public static function findAll(){
//        echo '<br>вызов из класса '.get_called_class().'<br>';
//        echo '<br>вызов данных из таблицы '.static::TABLE .'<br>';
//        echo '<br>вызов из класса '.static::class.'<br>';
        $db = new Db();
        //позднее связывание для вызова с параметрами класса наследника
        //это позволит вернуть результат сразу в виде массива типа класса наследника
        $res = $db->query('SELECT * FROM '.static::TABLE .' ;',static::class );
//        var_dump('<br>$res = '.$res.'<br>');
        return $res;
    }

    public static function findAllOrderByName(){
//        echo '<br>вызов из класса '.get_called_class().'<br>';
//        echo '<br>вызов данных из таблицы '.static::TABLE .'<br>';
//        echo '<br>вызов из класса '.static::class.'<br>';
        $db = new Db();
        //позднее связывание для вызова с параметрами класса наследника
        //это позволит вернуть результат сразу в виде массива типа класса наследника

        $res = $db->query("SELECT * FROM ".static::TABLE ." ORDER BY name ;",static::class );
//        var_dump('<br>$res = '.$res.'<br>');
        return $res;
    }

    public static function getAllField(){
        echo 'вызов полей идет из класса '.get_called_class();
//        echo Нужно отметить, что для большего удобства в PHP кроме слова «static» есть еще специальная функция get_called_class(), которая сообщит вам — в контексте какого класса в данный момент работает ваш код.

        $s = new static;
        $f = [];
        foreach ($s as $nameField => $valueField ){
            array_push($f, $nameField);
        }
        return $f;
    }
    /**
     * @param int $id
     * @return bool вернет обЪект по id если есть такой иначе вернет false
     */
    public function findObjById( $id){
         return findObjByIdcStatic( $id);
    }
    /**
     * @param static method return object for $id or false if obj whith $id not Exist
     * @param int $id
     * @return bool вернет обЪект по id если есть такой иначе вернет false
     */
    public static function findObjByIdStatic($id){
//        echo ('<br>вызов из класса ModelLikeTable начал<br>');
        $db = new Db();
        $query = 'SELECT * FROM '.static::TABLE.' WHERE  '.static::NAME_ID.' = '.$id.';';
//        echo "из ModelLikeTable  запрос $query ";
        $res = $db->query($query, static::class);
        
        //через позд связывание позволит вернуть объект вызванного класса
//        $res = $db->query($query, static::class);
//        var_dump('<br> результат запроса $res ----> '. $res .'<br>');
//        echo ('<br> вызов из ModelLikeTable.php конец <br>');

        if(false!==$res)
            return $res[0];
        else return false;
    }
    
    /**
     * this method mast be in extens class
     */
    abstract public function isNew();
        /*
{
     if(empty( $this->id_user ))
         return true;
     else return false;
 }
*/
    /**
     * @return bool
     * @param method to insert only new object to table
     * @param isNew()must have in extend Class
     */
    public function insert(){
        if(! $this->isNew())
            return false;
        $columns = [];
        $values = [];
        foreach ($this as $k=>$v){
            if(static::NAME_ID == $k){
                continue;
            }
            $columns[] = $k;
            $values[':'.$k] = $v;
        }
//        var_dump(get_class($this));
//        var_dump($columns);
//        var_dump($values);
//         echo '<br>вызов из класса '.static::class.' передался в класс '.self::class .'<br>';
        $sqlQeryInsert = 'INSERT INTO '.static::TABLE. '('.implode(',',$columns ).') VALUES('.implode(',',array_keys($values)).');';
//        echo  '<br>запрос на добавление  '.$sqlQeryInsert.'<br>';
//        die('выход из инсерта');
        $db = new Db();
        $res=false;
        $mes = "";
        try{
            $res = $db->execute($sqlQeryInsert,$values);
            return $res;
        }catch (\PDOException $mes){
            return false;
        }
//        return $res;
    }

    /**
     * @param int $_id
     * @return bool if update true return true else return false
     */
    public function update(){
        if($this->isNew())
            return false;

        $columns = [];
        $values = [];
        $queryStrUpdate = 'UPDATE '.static::TABLE.' SET ';
        foreach ($this as $k => $v){
            if(static::NAME_ID == $k || $v == NULL){
                continue;
            }
            $values [':'.$k] = $v;
            $queryStrUpdate .= $k .' = :'.$k.',';
        }
        $queryStrUpdate = trim($queryStrUpdate,',');//убарали последнюю запятую
        $queryStrUpdate .= ' WHERE '.static::NAME_ID.' = '.$this->id.';';
        if(! empty($values)){
            $db = new Db();
//            foreach ($values as $k => $v){// для перевода на другую строку &lt;br&gt;
//                echo ("  параметры для update $k : $v <br>");
//            }
//            die();
            $res = $db->execute($queryStrUpdate, $values);
            return $res;
        }
        return false;
    }

    
    //поиск по  подобному назаванию в любой таблице (естественно должно быть поле name
    public static function searchAllForLikeName( $likeName){
        $db = new Db();
        $query  = "SELECT * FROM ".static::TABLE ." WHERE `name` LIKE '%".$likeName."%';";
//       echo "$query";
//        die();
        $res = $db->query($query ,static::class );
//        var_dump('<br>$res = '.$res.'<br>');
        return $res;

    }
    //проверка существует ли объект с таким именем
    public static function ifExistObjWithName( $name){
        $db = new Db();
        $query  = "SELECT * FROM ".static::TABLE ." WHERE `name`  = '$name';";
        $res = $db->query($query ,static::class );
//        var_dump('<br>$res = '.$res.'<br>');
        return $res;

    }
//проверка существует ли объект с таким phone0 номером телефона   
    public static function ifExistObjWithPhone0( $phone0){
        $db = new Db();
        $query  = "SELECT * FROM ".static::TABLE ." WHERE `phone0`  = '$phone0';";
        $res = $db->query($query ,static::class );
//        var_dump('<br>$res = '.$res.'<br>');
        return $res;
    }
//проверка существует ли объект с таким email0
    public static function ifExistObjWithEmail0( $email0){
        $db = new Db();
        $query  = "SELECT * FROM ".static::TABLE ." WHERE `email0`  = '$email0';";
        $res = $db->query($query ,static::class );
//        var_dump('<br>$res = '.$res.'<br>');
        return $res;

    }

    /**
     * @param int $_id
     * @return bool if delete in table return true else return false
     */
    public function delete($_id){
        if($_id< 1)
            return false;
        $queryStrDelete = 'DELETE FROM '.static::TABLE.' WHERE '.static::NAME_ID. ' = '.$_id;
        $mes = "";
        $res =false;
        $db = new  Db();
        try{
            $res = $db->execute($queryStrDelete,NULL);
            return $res;
        }catch (\PDOException $mes){
            return false;
        }
//        return $res;
    }

    /**
     * @param int $_id
     * @return bool if delete in table return true else return false
     */
    public static function deleteObj($_id){
        if($_id< 1)
            return false;
        $queryStrDelete = 'DELETE FROM '.static::TABLE.' WHERE '.static::NAME_ID. ' = '.$_id;
        $db = new  Db();
        $res = $db->execute($queryStrDelete,NULL);
        return $res;
    }
    //поиск объектов по подобию name or addCharacteristic можно применять к классам Supplier Material
    public static function searchObjectsLikeNameOrLikeAddCharacteristic(  $likeNameAddChar){
        $db = new Db();
        $query  = "SELECT * FROM ".static::TABLE ." WHERE `name` LIKE '%".$likeNameAddChar."%' OR `addCharacteristic` LIKE '%".$likeNameAddChar."%' ;";
//        echo "query";
//        die();
        $res = $db->query($query ,static::class );
//        var_dump('<br>$res = '.$res.'<br>');
        return $res;


    }

    //поиск объектов по подобию name можно применять к всем классам
    public static function searchObjectsLikeName(  $likeNameAddChar){
        $db = new Db();
        $query  = "SELECT * FROM ".static::TABLE ." WHERE `name` LIKE '%".$likeNameAddChar."%' ;";
//        echo "query";
//        die();
        $res = $db->query($query ,static::class );
//        var_dump('<br>$res = '.$res.'<br>');
        return $res;


    }
}