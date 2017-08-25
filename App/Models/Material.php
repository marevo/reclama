<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 26.06.2017
 * Time: 21:40
 */

namespace App\Models;
use App\Db;
use App\ModelLikeTable;

class Material extends ModelLikeTable
{
    public $id;
    public $name;
    public $addCharacteristic;
    public $measure;
    public $deliveryForm;
    public $priceForMeasure;
    public $id_suppliers;

    const TABLE = 'materials';
    const NAME_ID ='id';
//    public $nameSupplier;
    public function isNew()
    {
        // TODO: Implement isNew() method.
        if(empty($this->id) || is_null( $this->id)){
            return true;
        }
        else{
            return false;
        }
    }
   

}