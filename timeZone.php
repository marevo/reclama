<?php
/**
 * Created by PhpStorm.
 * User: marevo
 * Date: 14.09.2017
 * Time: 10:00
 */
//print_r( DateTimeZone::listIdentifiers( ) );
$added_time = time( ) + ( 0 * 60 * 60 );
//echo " UNIX время на сервере $added_time";
$thistime = date( "Y-m-d H:i", $added_time );
echo "$thistime";
//echo "<script type='text/javascript'>$('li:last_child').html(' $thistime ');</script>;";
