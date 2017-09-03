<?php
//можем здесь писать если просто вывод или пока что при подключении будет autoload.php в head.html
require '../autoload.php';
?>
    <!DOCTYPE HTML>
    <html lang="ru-RU">
    <?php
    //подключим head.html
    require_once('../head.html');
    ?>
    <body>
    <div class="container">
        <div class="row">
            <?php require_once('header.html'); ?>
        </div>
        <div class="row"><!-- навигация -->
            <?php require('../navigation.html'); ?>
            <script>
                showLi('контакты');
            </script>

        </div>
        <div class="row">
            <div class="col-lg-2 backForDiv">
                этот див слева от таблицы в нем можно расположить дополнительные кнопки добавить редактировать удалить
            </div>
            <div class="col-lg-10 divForTable">
                <div class="row">
                    <div class="col-lg-1 text-center">
                        контакты
                    </div>
                    <div class="col-lg-4">
                        <form action="#">
                            <div class="row">
                                <div class="col-lg-12">
                                    <input type="text">
                                    <button>
                                        <liнайти
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <?php
                        //вызовем отображение таблицы через трэйт
                        echo \App\Models\ContactsClient::showAllFromTable('tableContactsClient', \App\Models\ContactsClient::findAll());
                        ?>

                    </div>

                </div>
            </div>
        </div>

    </div>
    </body>
    </html>
<?php
//$allSuppliers = \App\Models\Supplier::getAllSuppliers();
//
////var_dump($allSuppliers);
//showAllSupliers($allSuppliers);

