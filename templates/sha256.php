<?
/*if($_POST["sql"])
{
//	$command = "mysql -uroot -D reclama < {$_FILES['sql']['tmp_name']}";
//	$message = shell_exec($command);
//    $mysqli = mysqli_connect("localhost", "root", NULL, "reclama");
//	mysqli_multi_query($mysqli,$_POST["sql"]);
//	file_put_contents("reclama_debug.log",$_POST["sql"]);
    
}
*/
   
   echo hash_file("sha256",$_FILES['file']['tmp_name']);
  // echo "test";
   
?>