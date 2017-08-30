<?
require '../autoload.php';
if(session_start())
{
	$sid=session_id();
    $res=\App\Models\User::getCurrentUserBySession($sid);
	if($res && time()-$res[0]->updated<1800)
	{
		
		$updated=\App\Models\User::createSession($res[0]->login);
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
		echo "unauthorized";
//		echo $sid;
 //       echo $res[0]-;
	}
}
else
{
	echo "error";
}
