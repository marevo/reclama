<?
    require_once('./head.php');
?>
<div class="container">
<div class="row">
<div class="col-xs-12 col-sm-12 col-lg-6">
<div class="panel panel-primary">
<div class="panel-heading">
<h3 class="panel-title">
Авторизация на сайте</h3>
</div>
<div class="panel-body">
<div class="row">

<div class="col-xs-6 col-sm-6 col-md-6 login-box">
<form role="form" name="authorization">
<div class="input-group">
<span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
<input type="text" class="form-control" placeholder="Имя пользователя" required autofocus name="login"/>
</div>
<div class="input-group">
<span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
<input type="password" class="form-control" placeholder="Ваш пароль" required name="password"/>
</div>
<!--<p>
<a href="#">Забыли свой пароль?</a></p>
У вас нет аккаунта? <a href="#">Регистрация</a>
-->
</form>
</div>
</div>
</div>
<div class="panel-footer">
<div class="row">
<!--<div class="col-xs-6 col-sm-6 col-md-6">
<div class="checkbox">
<label>
<input type="checkbox" value="Remember">
Запомнить меня
</label>
</div>
</div>
-->
<div class="col-xs-6 col-sm-6 col-md-6">
<button type="button" class="btn btn-labeled btn-success" id="authorization">
<span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span>Войти</button>
<button type="button" class="btn btn-labeled btn-danger">
<span class="btn-label"><i class="glyphicon glyphicon-remove"></i></span>Выход</button>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<script>
    document.getElementById("authorization").onclick=function() {
		// создать объект для формы
        var formData = new FormData(document.forms.authorization);
        // отослать
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "/templates/createSession.php", false);
	    xhr.overrideMimeType("text/plain; charset=utf8");
        xhr.send(formData);
		if(xhr.responseText=="authorized")
		{
			window.location="/";
		}
		else if(xhr.responseText!="unauthorized")
		{
			console.log(xhr.responseText);
		}
}
</script>