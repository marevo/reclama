<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 27.06.2017
 * Time: 13:44
 */

namespace App\Models;
use App\ModelLikeTable;

class ContactsClient extends ModelLikeTable
{
    public $id;
    public $name;
    public $id_clients;
    public $phone;
    public $email;
    
    const NAME_ID ='id';
    const TABLE ='contactsclients';
    
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