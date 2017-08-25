<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 21.06.2017
 * Time: 19:36
 */
function __autoload($class){
    //App\Models\Supplier =>./App/Models/Supplier.php
    require  __DIR__.'/'.str_replace('\\','/' ,$class ).'.php';
}