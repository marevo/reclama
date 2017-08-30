<!DOCTYPE html>
<html>
<!--<head>-->
<!-- подключим к странице head.php заголовки и линковку-->
<!--    <meta http-equiv="content-type" content="text/html;charset=utf-8">-->
<!--    <meta name="viewport" content="width=device-width, initial-scale=1.0">-->
<!--    <title>изготовление наружной рекламы в Чернигове акрилайт , баннер , вывеска , лайтбокс , лайтпостер , плакат , постер , призматрон ,  </title>-->
                                            <!-- js код -->
                              <!--<script src="http://code.jquery.com/jquery-latest.min.js"></script>-->
                       <!-- или так-->
               <!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script> -->
<!--    <script type="text/javascript" src="js/jquery.min.js"></script>-->
<!---->
<!--    <link rel="stylesheet" href="css/bootstrap.min.css">-->
<!--    <link rel="stylesheet" href="css/bootstrap-theme.min.css">-->
<!--    <link rel="stylesheet" href="css/manual.css">-->
<!--    <link rel="icon" href="fonts/glyphicons-halflings-regular.ttf">-->
<!--    <link rel="shortcut icon"  href="img/favicon/icon.ico">-->
<!--    <script src="js/bootstrap.min.js"></script>-->
<!--    <script type="text/javascript" src="js/ajax_post_get.js"></script>-->
<!--</head>-->
<?php
      require_once ('./head.php');
//так работает
//   require_once ("$_SERVER[DOCUMENT_ROOT]/head.php");
?>
<body>

<div class="wrapper">
    <div class="row">
        <!--        подтянем header сайта если мы его помещаем в контейнер, то навигация становится на всю ширину-->
        <!-- относительно точки входа index.php !-->
        <?php require_once ('./templates/header.php'); ?>
    </div>
    <div class="middle">
        <!--    подтянем menu сайта почему оно идет по всей ширире ? по идее это все в контейнере должно быть на ширину рисунка header.php-->
        <?php require_once ('./navigation.php'); ?>
<div class="footer">
            <div> статус: admin/user<br>имя пользователя/ник</div>      
        </div>
    </div>
    
          
</div>
</body>
</html>

