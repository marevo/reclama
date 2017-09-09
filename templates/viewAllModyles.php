<h1>Список установленных модулей</h1><ul><?
foreach (glob("modules/*.ttl") as $filename)
{
	echo "<li>";
    include $filename;
	echo "</li>";
}
?></ul><form enctype="multipart/form-data" method="post" action="templates/module_installer.php" id="form_file"><p>Установите модуль на сервер</p>
<p><input type="file" name="file" id="file" accept="application/zip">
<input type="button" id="send" value="Установить"><div class="progress-bar">
<span class="progress-bar-fill" style="width: 30%"></span></div>
</p></form>
<div>Контрольная сумма:<div id="sha256sum"></div>
<script>
document.getElementById("send").onclick=function() {
                 var formData=new FormData(document.getElementById("form_file"));
			     var xhr = new XMLHttpRequest();
                 xhr.open("POST", "/templates/module_installer.php");
                 xhr.send(formData);
			 };

function sha256() {
                 var formData=new FormData(document.getElementById("form_file"));
			     var xhr = new XMLHttpRequest();
                 xhr.open("POST", "/templates/sha256.php",false);
                 xhr.send(formData);
				 document.getElementById("sha256sum").innerHTML=xhr.responseText;
};
document.getElementById("file").addEventListener('change',sha256);
</script>