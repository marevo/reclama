<!-- как правильнее подключать ? через перем сервер документ рут или через /     -->
<!--    <div class="col-lg-12 header">-->
<!--        <div class="row">-->
<!--            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">-->
<!--            <img src='--><?php //$_SERVER[DOCUMENT_ROOT]?><!--/img/Acc-logo200-120.jpg' -->
<!--                 alt="imgLogo" class="img-rounded img-responsive text-right">-->
<!--            </div>-->
<!--             <div class="col-lg-9" ><span class="text-center ">ADVERTISING CREATE COMPANY</span></div> -->
<!--            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9" >-->
<!--                <img src="--><?php //$_SERVER[DOCUMENT_ROOT]?><!--/img/viveska2.jpg" -->
<!--                     alt="nameFirma" class="img-rounded img-responsive text-left">-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>

    <div class="col-lg-12 header">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                <img src='/img/Acc-logo200-120.jpg'
                     alt="imgLogo" class="img-rounded img-responsive text-right">
            </div>
                       <div class="col-lg-9" ><span class="text-center ">ADVERTISING CREATE COMPANY</span></div>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9" >
                <img src="/img/viveska2.jpg"
                     alt="nameFirma" class="img-rounded img-responsive text-left">
            </div>
        </div>
    </div>
-->
<div id="header">
<div class="container">
<div id="logo"></div>
<div id="exit"><a>
<!--<img src="./img/vihod.png" align= "absmiddle" vspace="10" hspace="5" />-->
<i class="icon-exit3"></i>Выход</a></div>
</div>
</div>
<script type="text/javascript">
$(document).ready(function () {
      var offset = $('#header').offset();
    var topPadding = 0;
    $(window).scroll(function() {
        if ($(window).scrollTop() > offset.top) {
			$('#header').css('margin-top',$(window).scrollTop() - offset.top + topPadding);
           // $('#header').stop().animate({marginTop: $(window).scrollTop() - offset.top + topPadding});
        }
        else {
			$('#header').css('margin-top',0);
           // $('#header').stop().animate({marginTop: 0});
        }
    });
});
document.getElementById("exit").onclick=function(){
	var xhr = new XMLHttpRequest();
        xhr.open("POST", "/templates/deleteSession.php", false);
	    xhr.overrideMimeType("text/plain; charset=utf8");
        xhr.send(null);
    	window.location="authorization.php";
}
</script>
