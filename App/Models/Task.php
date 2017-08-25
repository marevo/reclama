<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 29.06.2017
 * Time: 9:26
 */

namespace App\Models;


use App\FastViewTable;
use App\ModelLikeTable;

class Task extends ModelLikeTable
{
    public $id;
    public $name;
    public $content;
    public $deadline;//до какой даты надо сделать
    public $idUser;
    public $priority;
    public $status;
    public $dateAppointment;//дата назначения задачи
    public $dateCompletion;//дата выполнения

    const TABLE ='tasks';
    const NAME_ID = 'id';
    
    use FastViewTable;
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