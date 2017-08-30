<?
require '../autoload.php';
if(session_start())
{
//	$sid=session_id();
    $res=\App\Models\User::getCurrentUserByLogin($_POST["login"]);
	if($res && password_verify($_POST["password"],$res[0]->password))
	{
		$updated=\App\Models\User::createSession($_POST["login"]);
		if($updated)
		{
		    echo "authorized";
		}
		else
		{
			echo $res;
		}
	}
	else
	{
		echo "authorized";
	}
}
else
{
	echo "error";
}