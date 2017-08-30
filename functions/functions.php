<script>
var selectedId;
function activate (id,handler) {
	document.getElementById(id).style.fontWeight='bold';
	if(selectedId!=null)
	{
	    document.getElementById(selectedId).style.fontWeight='normal';
	}
	selectedId=id;
	if(handler==="")
	{
		document.getElementById("main_modul").innerHTML="";
		return;
	}
	var element=document.getElementById(id);
	var parent=element.parentElement;
	var children = parent.children;
	for (var i = 0; i < children.length; i++) {
        var grandchildren = children[i].children;
		
		for(var j=0;j<grandchildren.length;j++) {
			var grandchild=grandchildren[j];
			if(grandchild.tagName==="UL"){
			    grandchild.style.display="none";
			}
		}
	}
	var elementChildren=element.children;
	for (var i = 0; i < elementChildren.length; i++) {
		var child=elementChildren[i];
		child.style.display="block";
	}
	var xmlHttp = new XMLHttpRequest();
    xmlHttp.open( "GET", "/templates/checkSession.php", false ); // false for synchronous request
	xmlHttp.overrideMimeType("text/plain; charset=utf8");
    xmlHttp.send( null );
    if(xmlHttp.responseText=="authorized")
	{
	    var xmlHttp = new XMLHttpRequest();
        xmlHttp.open( "GET", handler, false ); // false for synchronous request
	    xmlHttp.overrideMimeType("text/plain; charset=utf8");
        xmlHttp.send( null );
        document.getElementById("main_modul").innerHTML=xmlHttp.responseText;
	}
	else if(xmlHttp.responseText=="unauthorized")
    {
	    window.location="/authorization.php"
    }
	else 
	{
	    document.getElementById("main_modul").innerHTML=xmlHttp.responseText;
	}
}
</script>
<?
    function get_menu(){
		$mysql_host = 'localhost';
        // MySQL username
        $mysql_username = 'root';
		$mysql_database = 'reclama';
		$mysql_password = NULL;
		$sql="SELECT * FROM menu";
		$mysqli = mysqli_connect($mysql_host, $mysql_username,$mysql_password,$mysql_database);
//		mysql_select_db($mysql_database);
		$result = mysqli_query($mysqli,$sql);
		if(!$result) {
			return NULL;
		}
		$arr_cat = array();
		if(mysqli_num_rows($result) != 0) {
			for($i = 0; $i < mysqli_num_rows($result);$i++) {
				$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
				if(empty($arr_cat[$row['parent_id']])) {
					$arr_cat[$row['parent_id']] = array();
				}
				$arr_cat[$row['parent_id']][] = $row;
			}
			return $arr_cat;
		}
	}
	function view_menu($arr,$parent_id = 0) {
		if(empty($arr[$parent_id])) {
			return;
		}
		$hidden="";
		if($parent_id>0){
			$hidden=' style="display:none"';	
		}
		echo '<ul'.$hidden.'>';
		for($i = 0; $i < count($arr[$parent_id]);$i++) {
			$id=$parent_id . "sub" . $i;
			
			echo '<li id="'.$id.'"><a  name="'.$arr[$parent_id][$i]['handler'].'" onclick="activate(\''.$id.'\',\''.$arr[$parent_id][$i]['handler'].'\')">'
						.$arr[$parent_id][$i]['title'].'</a>';
	        view_menu($arr,$arr[$parent_id][$i]['id']);
			echo '</li>';
		}
		echo '</ul>';
	}
?>