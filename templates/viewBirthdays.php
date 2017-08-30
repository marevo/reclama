<script>
document.getElementById("clients_by_birtday").onclick = function() {
						console.log("test");
						var formData = new FormData(document.forms.birthday_dates);
						var xmlHttp = new XMLHttpRequest();
                        xmlHttp.open( "POST", "/controllerBirthday.php", false ); // false for synchronous request
	                    xmlHttp.overrideMimeType("text/plain; charset=utf8");
                        xmlHttp.send( formData );
                        document.getElementById("birthday_module").innerHTML=xmlHttp.responseText;
					};
 </script> 
  <div class="row">
            <!--            начало доп блока слева-->
            <div class="col-lg-2 backForDiv">
                этот див слева от таблицы в нем можно расположить дополнительные кнопки добавить редактировать удалить
            </div>
            <!--            конец доп блока слева-->
            <div class="col-lg-10 backForDiv divForTable">
			    <form name="birthday_dates" action="/controllerBirthday.php" method="POST">
                    <div class="form-group">
                    <label for="inputDate">Введите начальную дату:</label>
                    <input type="date" class="form-control" name="start_date">
					<label for="inputDate">Введите конечную дату:</label>
                    <input type="date" class="form-control" name="end_date">
					<input type="button" value="Вывести" id="clients_by_birtday">
					<input type="submit" value="Submit">
                    </div>
                </form>
				<div id="birthday_module">
				</div>
            </div>
        </div>
               