<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 28.06.2017
 * Time: 13:25
 */

namespace App\Models;
use App\ModelLikeTable;
use App\FastViewTable;

class MaterialsToOrder extends ModelLikeTable
{
    public $id;
    public $idOrder;
    public $idMaterials;
    public $countNeed;
    public $priceCountNeed;
    public $recomAddCount;
    public $priceRecomNeed;

    const TABLE = 'materialsToOrder';
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

}