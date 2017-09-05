<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 27.06.2017
 * Time: 23:14
 */

namespace App;


trait FastViewTable
{
  public static function showAllFromTable($idTable, $arrAll = [])
    {
        if (isset($arrAll) && !is_null($arrAll) && isset($arrAll[0])) {
            //для просмотра полей(будем их отображать в заголовках столбцов таблицы всех пользователей 
            $fieldsInTable = [];
//        $fieldsInSupplier = \App\Models\Supplier::getAllField();
//            если не пустой массив полей для показа будем только его выводить в заголовок
                foreach ($arrAll[0] as $k => $v) {
                    array_push($fieldsInTable, $k);
            }
            //теперь в строку сверстаем таблицу
            $tableAll = '';
            $tableAll .= "<table id = '$idTable' , class='table-hover'><thead><tr>";
            //заполним названиями полей в таблице строку заголовков
            foreach ($fieldsInTable as $namePole):
                if($namePole =='id')
                    continue;
                $tableAll .= "<td>$namePole</td>";
            endforeach;
//        var_dump( 'поля таблицы '.$fieldsInSupplier);
            $tableAll .= '</tr></thead><tbody>';
            //проходим строку записи
            foreach ($arrAll as $arrItem):
                $tableAll .= "<tr>";
                //проходим по строке $k название поля, $value значени поля
                foreach ($arrItem as $k =>  $value):
                    if(in_array($k,$fieldsInTable )){
                        if($k=='id'){
                            $data_id = $value;
                            continue;
                        }
                        $tableAll .= "<td class='textBold'>$value</td>";
                    }else continue;
                endforeach;
                $tableAll .= "</td><td data-id='$data_id'><!-- glyphicon-wrench -->
<button data-id='$data_id'><span class='glyphicon glyphicon-edit'></span></button></td><td>
<button data-id='$data_id'><span class='glyphicon glyphicon-trash'></span></button></td></tr>";
            endforeach;
            $tableAll .= '</tbody></table>';
            //раньше выводили на лету. теперь сверстав строку выше выведем всю таблицу в div id= 'divForTable'
            // так не работает return '<script>document.getElementById("divForTable").innerHTML("'.$tableAllSupplers.'") </script>';

            return $tableAll;

        }
        else{
            return'<div class="alert-info text-info text-center">пока нет данных</div>';
        }

    }
    
    //для вывода успех не успех в 
    public static function showUspeh(string $str){
        echo "<script>fUspehAll('$str');</script>";
    }
    public static function showNoUspeh(string $str){
        echo "<script>fNoUspehAll('$str');</script>";
    }
}

