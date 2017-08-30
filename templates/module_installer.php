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
   $zip = new ZipArchive;
   if ($zip->open($_FILES['file']['tmp_name']) === TRUE) {
    $zip->extractTo('../tmp');
    $zip->close();
	chdir("../tmp");
	$handle = @fopen("installer.ins", "r");
	if ($handle) {
        while (($buffer = fgets($handle)) !== false) {
            $params=explode("=",$buffer);
			if($params[0]=='SQL')
			{
				
				$filename=trim($params[1]);
                // MySQL host
                $mysql_host = 'localhost';
                // MySQL username
                $mysql_username = 'root';
                // MySQL password
                $mysql_password = NULL;
                // Database name
                $mysql_database = 'reclama';

                // Connect to MySQL server
                $mysqli = mysqli_connect($mysql_host, $mysql_username,$mysql_password,$mysql_database) or file_put_contents("reclama_debug.log",'Error connecting to MySQL server: ' . mysql_error());
                // Select database
//                mysql_select_db($mysql_database) or file_put_contents("reclama_debug.log",'Error selecting MySQL database: ' . mysql_error());

                // Temporary variable, used to store current query
                $templine = '';
                // Read in entire file
                $lines = file("./" . $filename);
                file_put_contents("reclama_debug.log",error_get_last());
                // Loop through each line
                foreach ($lines as $line)
                {
                    // Skip it if it's a comment
                    if (substr($line, 0, 2) == '--' || $line == '')
                        continue;

                    // Add this line to the current segment
                    $templine .= $line;
                    // If it has a semicolon at the end, it's the end of the query
                    if (substr(trim($line), -1, 1) == ';')
                    {
                        // Perform the query
                        mysqli_query($mysqli,$templine) or file_put_contents("reclama_debug.log",'Error performing query \'<strong>' . $templine . '\': ' . mysql_error() . '<br /><br />');
                        // Reset temp variable to empty
                        $templine = '';
                    }
                }
			}
			else if($params[0]=='DIR')
			{
				$dirname=trim($params[1]);
				mkdir("../modules/" . $dirname);
			}
			else 
			{
				$filename=trim($params[1]);
				rename($filename,"../modules/" . $filename);
			}
        }
        fclose($handle);
    }
	//rmdir('../tmp/*');
   }
?>