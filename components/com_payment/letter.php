<?php

function template($string, $params)
{
    $result = $string;
    foreach(array_keys($params) as $key) {
        $result = preg_replace(
            '/\\[' . preg_quote($key, '/') . '\\]/'
            , $params[$key]
            , $result
        );
    }
    return $result;
}

function letterTemplate()
{
    return '
<!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8" />
    <title></title>
    <link rel="stylesheet" href="letter.css" />
    <link rel="stylesheet" href="http://dolphinevpatoria.ru/templates/jblank/css/_font-awesome.min.css" />
    <style type="text/css">
        * {
            font-family: "OpenSans";
        }
        #logo-right {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            color: blue;
        }
        .logo-right-big-font {
            font-size: 22px;
        }
        table {
            width: 100%;
        }
        .center-positioned {
            text-align: center;
        }
        .center-positioned img {
            max-width:100%;
            max-height:100%;
        }
        table tr {
            width: 100%;
        }
        #main-text {
            color: rgb(90,90,90);
            font-size: 50px;
        }
        #middle-text {
            color: rgb(90,90,90);
            font-size: 38px;
        }
        .middle-darkgrey-text {
            font-size: 44px;
            color: rgb(59,58,58);
            text-align: left;
        }
        .aligned-darkgrey-text {
            color: rgb(90,90,90);
            font-size: 38px;
        }
        .phones {
            color: blue;
        }
        #addinfo {
            color: rgb(90,90,90);
        }
        #lightgrey {
            color: rgb(182,182,182);
        }
        #additional-info-table {
            border:0px solid white;
            background: url([domain]media/bckg.jpg);
            border-collapse: collapse;
        }
        #additional-info-table td{
            border:0px solid white;
        }
        .wight-bkg {
            background-color: white;
        }
        .wight-bkg td {
            border: 0px;
        }
        td {
            border-color: white;
        }
    </style>
</head>

<body>
<table>
    <tr>
        <td>
            <img src="[domain]media/biglogo.jpg"/>
        </td>
        <td>
            <div id="logo-right">
                Республика Крым, г. Евпатория, ул. Киевская 19/20<br/>
                <span class="logo-right-big-font">+7(978)855-46-51</span><br/>
                <span class="logo-right-big-font">+7(36569)2-70-99</span>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div class="center-positioned">
                <img src="[domain]media/dolphin.jpg"/>
            </div>
        </td>
    </tr>
</table>
<table id="additional-info-table"
    <tr>
        <td colspan="2">
            <div class="center-positioned">
                <br/><br/><br/><br/>
                <span id="main-text">
                        ОПЛАТА ПРОШЛА УСПЕШНО!
                    </span>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div class="center-positioned">
                <br/><br/><br/><br/>
                <span id="middle-text">
                        Благодарим за использование нашего онлайн сериса покупки билетов.
                        <br/>
                        Оплата прошла успешно, Ваш билет представлен ниже на данной странице.
                    </span>
            </div>
        </td>
    </tr>
    <tr class="wight-bkg">
        <td>
            <img src="[domain]media/biglogo.jpg"/>
        </td>
        <td>
            <span class="middle-darkgrey-text">
                ВХОДНОЙ БИЛЕТ
            </span>
        </td>
    </tr>
    <tr class="wight-bkg">
        <td>
            <div class="center-positioned">
                <img src="[domain][qr]"/>
            </div>
        </td>
        <td>
            <span class="aligned-darkgrey-text">Дата: [date]</span><br/><br/>
            <span class="aligned-darkgrey-text">Время: [time]</span><br/><br/>
            <span class="aligned-darkgrey-text">Цена: [price]</span><br/><br/>
            <span>ПРАВИЛА ПРОХОДА ПО ЭЛЕКТРОННОМУ БИЛЕТУ</span><br/>
            <span>Посетители по категориям "ДЕТСКИЙ" должны предъявить на контроле ПОДТВЕРЖДАЮЩИЕ ДОКУМЕНТЫ (свидетельство о рождении). При отсутствии подтверждающих билетов проход по билету невозможен, билет обмену и возврату не подлежит.</span><br/>
            <span>В случае опоздания покупателя к моменту оказания услуги и/или не посещения мероприятия, услуги считаются оказанными надлежащим образом, стоимость билета не возвращается.</span>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <br/><br/><br/>
            <div class="center-positioned">
                ВНИМАНИЕ! БИЛЕТ ДЕЙСТВИТЕЛЕН ДЛЯ ОДНОКРАТНОГО ПРОХОДА
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <br/>
            <div class="center-positioned">
                <span id="addinfo">
                    Будем рады видеть Вас в указанные в билете дату и время в нашем дельфинарии, расположенному по адресу: Республика Крым, г. Евпатория, ул. Киевская 19/20
                </span>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="2" id="lightgrey">
            <br/>
            <div class="center-positioned">
                Республика Крым, г. Евпатория, ул. Киевская 19/20
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div class="center-positioned">
                <span class="phones">+7(978)855-46-51, +7(36569)2-70-99</span><br/>
            </div>
        </td>
    </tr>
</table>
</body>
</html>
<?php '; } ?>
