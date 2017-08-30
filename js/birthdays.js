				    document.getElementById("clients_by_birtday").onclick = function() {
						console.log("test");
						var formData = new FormData(document.forms.birthday_dates);
						var xmlHttp = new XMLHttpRequest();
                        xmlHttp.open( "POST", "/controllerBirthday.php", false ); // false for synchronous request
	                    xmlHttp.overrideMimeType("text/plain; charset=utf8");
                        xmlHttp.send( formData );
                        document.getElementById("birthday_module").innerHTML=xmlHttp.responseText;
					};
