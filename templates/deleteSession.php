<?
require '../autoload.php';
if(session_start())
{
$session=session_id();
$deleted=\App\Models\User::deleteSession($session);
}
?>