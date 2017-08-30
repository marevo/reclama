//################################################# ФОРМИРОВАНИЕ И ОТПРАВКА jQuery
//!!!ПРИ ИСПОЛЬЗОВАНИИ ПОДКЛЮЧИТЕ jQuery!!!
//!!!ВЫЗОВ - jquery_send(elemm, 'post', 'function.php', ['param'], [param.value])
var req;
var elem    //!!! - ГЛОБАЛЬНАЯ (т.к. для post_send(get_send) и func_response) elem - ЭЛЕМЕНТ ДЛЯ ВЫВОДА
//################################################# ФОРМИРОВАНИЕ И ОТПРАВКА POST
function jquery_send(elemm, method, program, param_arr, value_arr) {
    var str='';                                                                //!!! - начальный str=''
    for(var i=0; i<param_arr.length; i++) {                                    //!!! - массивы - перебор
        str+=param_arr[i]+'='+encodeURIComponent(value_arr[i])+'&';             //!!! - накапливаем str
    }
    $.ajax(
        {
            type: method,
            url: program,
            data: str,
            success: function(data){
                $(elemm).html(data);
            } //!!! - ВЫВОД В ЭЛЕМЕНТ ТУТ - function(data){$('elemm').html(data);
        }
    );
}
//проверка подключен ли файл для работы с отправкой данных на сервер
//console.log('подключен ajax_post_get.js');
$('table').each(addEventListener('click',findTd ));
// функция выделения строки таблицы по клику на ней
//divId хранит id записи в выделенной строке( в кликнутой строке)
function findTd(event) {
    var target = event.target;//где был клик
    if(target.tagName != 'TD' || target.closest('THEAD')) return;//клик в неинтересном месте для нас
    //уберем подсветку во всей таблице кроме той строки в которой есть клик на ячейке
    $("table td[class ~= 'highLightTd']").removeClass('highLightTd');
    //подсветим ячейку где был клик и братьев ее, то есть выделим строку где был клик
    $(target).addClass('highLightTd').siblings().addClass('highLightTd');

    // $("table tr[class ~= 'highLightTd']").removeClass('highLightTd');
    //выделение строкт и дать бекграунд
    // $(target).parent().addClass('highLightTd');
}

//показ материалов по заказу при клике на строке в таблице заказа
//*фукция взятия текущей даты для вывода на любой странице
function getDate() {
    var d = new Date();
    var day = function () {
        if( d.getDate()<10)
            return '0'+d.getDate();
        else
            return d.getDate();
    }();
    var month = function () {
        if((d.getMonth() + 1 )<10)
            return '0'+( d.getMonth()+1);
        else return d.getMonth();
    }() ;
    var year = d.getFullYear();
    var date = year + "-" + month + "-" + day;
    return date;
}
//*/фукция взятия текущей даты для вывода на любой странице

    
