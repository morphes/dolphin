<?php
defined('_JEXEC') or die;
define('_JEXEC', 1);
define('JPATH_BASE', '../../../../..');//point to joomla root
define('DS', DIRECTORY_SEPARATOR);
require_once(JPATH_BASE . DS . 'includes' . DS . 'defines.php');
require_once(JPATH_BASE . DS . 'includes' . DS . 'framework.php');

$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('*')->from($db->quoteName('#__booking'))->group('start, title');
$db->setQuery($query);
$bookings = $db->loadObjectList();
$result = [];
foreach($bookings as $booking) {
    $result[$booking->start][] = $booking->title;
}

$query = $db->getQuery(true);
$query->select('*')->from($db->quoteName('#__time'))->group('time');
$db->setQuery($query);
$times = $db->loadObjectList();
$allTimes = [];
foreach($times as $time) {
    if($time->time) {
        $allTimes[] = $time->time;
    }
}
$childrenTitle = $prices[10]['description'];
$adultTitle = $prices[8]['description'];
?>

    <input type="hidden" id="bookings" value='<?php echo json_encode($result); ?>'/>
    <div id='frmFormMailContainer' class="reserve_block">
        <div class="title_reserve">
            <a href="/">
        <span>
            Главная
        </span>
            </a>
            <h1>
                Забронировать
            </h1>
        </div>

        <form name="frmFormMail" id="frmFormMail" target="submitToFrame" action='<?php echo PHPFMG_ADMIN_URL . ''; ?>'
              method='post' enctype='multipart/form-data' onsubmit='return fmgHandler.onSubmit(this);'>

            <input type='hidden' name='formmail_submit' value='Y'>
            <input type='hidden' name='type' value='zabronirovat'>
            <input type='hidden' name='mod' value='ajax'>
            <input type='hidden' name='func' value='submit'>


            <ol class='phpfmg_form'>

                <li class='field_block' id='field_0_div'>

                    <label class='form_field'>
                        <span>Ваше ФИО</span>
                        <input placeholder='Иванов Иван Иванович' type="text" name="field_0" id="field_0"
                               value="<?php phpfmg_hsc("field_0", ""); ?>" class='text_box'>
                    </label>
                    <div id='field_0_tip' class='instruction'></div>
                </li>

                <li class='field_block' id='field_1_div'>
                    <label class='form_field'>
                        <span>Номер телефона</span>
                        <input type="text" name="field_1" id="field_1" value="<?php phpfmg_hsc("field_1", ""); ?>"
                               class='text_box'>
                    </label>
                    <div id='field_1_tip' class='instruction'></div>
                </li>
                <li class='field_block' id='field_12_div'>
                    <label class='form_field'>
                        <span>Email</span>
                        <input type="text" name="field_12" id="field_12" value="<?php phpfmg_hsc("field_12", ""); ?>"
                               class='text_box'>
                    </label>
                    <div id='field_12_tip' class='instruction'></div>
                </li>

                <li class='field_block' id='field_2_div'>
                    <label class='form_field'>
                        <span>Время представления<br>*Время и дата должны соответствовать актуальному расписанию. Расписание в верхнем правом углу экрана на желтой плашке!</span>
                        <?php phpfmg_dropdown('field_2', implode('|', $allTimes)); ?>
                    </label>
                    <div id='field_2_tip' class='instruction'></div>
                </li>

                <li class='field_block' id='field_3_div'>
                    <label class='form_field'>
                        <span>День</span>
                        <?php
                        $dayString = "01|02|03|04|05|06|07|08|09|10|11|12|13|14|15|16|17|18|19|20|21|22|23|24|25|26|27|28|29|30|31";
                        $dayArray  = explode("|", $dayString);
                        foreach ($dayArray as $key => $day) {
                            if ($day == date('d')) {
                                $dayArray[$key] .= '=' . $dayArray[$key] . ',default';
                            }
                        } ?>
                        <?php phpfmg_dropdown('field_3', implode('|', $dayArray)); ?>
                    </label>
                    <div id='field_3_tip' class='instruction'></div>
                </li>

                <li class='field_block' id='field_4_div'>
                    <label class='form_field'>
                        <span>Месяц</span>
                        <?php $months = [
                            '01' => 'Январь',
                            '02' => 'Февраль',
                            '03' => 'Март',
                            '04' => 'Апрель',
                            '05' => 'Май',
                            '06' => 'Июнь',
                            '07' => 'Июль',
                            '08' => 'Август',
                            '09' => 'Сентябрь',
                            '10' => 'Октябрь',
                            '11' => 'Ноябрь',
                            '12' => 'Декабрь'
                        ];
                        if (isset($months[date('m')])) {
                            $months[date('m')] .= '=' . $months[date('m')] . ',default';
                        }
                        ?>
                        <?php phpfmg_dropdown('field_4', implode('|', $months)); ?>
                    </label>
                    <div id='field_4_tip' class='instruction'></div>
                </li>

                <div id="dolphin" style="display:none">
                    <li class='field_block' id='field_5_div' price="<?php echo $prices[5]['price']; ?>">
                        <label class='form_field'>
                            <span>Кол-во билетов</span>
                            <?php phpfmg_dropdown('field_5', "0|1=1,default|2|3|4|5|6|7|8|9|10|11|12|13|14|15|16|17|18|19|20|&gt;20"); ?>
                        </label>
                        <div id='field_5_tip' class='instruction'></div>
                    </li>

                </div>
                <div id="show" style="display:none">
                    <li class='field_block' id='field_8_div' price="<?php echo $prices[8]['price']; ?>">
                        <label class='form_field'>
                            <span><?php echo $adultTitle; ?></span>
                            <?php phpfmg_dropdown('field_8', "0|1=1,default|2|3|4|5|6|7|8|9|10|11|12|13|14|15|16|17|18|19|20|&gt;20"); ?>
                        </label>
                        <div id='field_8_tip' class='instruction'></div>
                    </li>
                    <li class='field_block' id='field_10_div' price="<?php echo $prices[10]['price']; ?>">
                        <label class='form_field'>
                            <span><?php echo $childrenTitle; ?></span>
                            <?php phpfmg_dropdown('field_10', "0=0,default|1|2|3|4|5|6|7|8|9|10|11|12|13|14|15|16|17|18|19|20|&gt;20"); ?>
                        </label>
                        <div id='field_10_tip' class='instruction'></div>
                    </li>
                </div>

                <li class='field_block' id='field_7_div'>
                    <label class='form_field'>
                        <span>Выберите программу</span>
                        <?php phpfmg_dropdown('field_7', "Шоу-программа"); ?>
                    </label>
                    <div id='field_7_tip' class='instruction'></div>
                </li>

                <li class='field_block' id='field_7_div'>
                    <label class='form_field form_field_checkbox'>
                        <?php phpfmg_checkboxes('field_7', '=1'); ?>
                        <span>Согласен с условиями <a href="/soglasheniye.html"
                                                      target="_blank">пользовательского соглашения</a></span>
                    </label>
                    <div id='field_0_tip' class='instruction'></div>
                </li>

                <li class='field_block' id='phpfmg_captcha_div'>
                    <?php phpfmg_show_captcha(); ?>
                </li>

                <li>

                    <div class="bottom_reserv">
                        <label class="form_field">
                            <span><h4>Стоимость заказанных билетов: <span id="price"></span> рублей</h4></span>
                        </label>
                        <br/>
                        <label class="form_field">
                            <span>Внимание! Бронирование билетов производится за сутки! Забронированные билеты необходимо выкупить в кассе дельфинария не позднее, чем за 30 минут до начала представления!</span>
                            <br/>
                            <span style="color: red;">Льготные билеты возможно приобрести только в кассах дельфинария при предъявлении оригиналов соответствующих документов</span>
                        </label>
                        <input type='submit' value='Забронировать билет' class='form_button'>
                    </div>

                    <div id='err_required' class="form_error" style='display:none;'>
                        <label class='form_error_title'>Все поля обязательны. Заполните их, пож-та!</label>
                    </div>


                    <span id='phpfmg_processing' style='display:none;'>
        <img id='phpfmg_processing_gif' src='/images/giphy.gif'
             border=0 alt='Processing...'> <label id='phpfmg_processing_dots'></label>
    </span>
                </li>

            </ol>
        </form>

        <iframe name="submitToFrame" id="submitToFrame" src="javascript:false"
                style="position:absolute;top:-10000px;left:-10000px;"/>
        </iframe>

    </div>

    <div id='thank_you_msg' style='display:none;'>
        <div class="reserv_modal">
            <div class="reserv_window">
                <div class="title_window">
                    Забронировано
                </div>
                <p>
                    Билет на шоу программу с дельфинами забронирован.
                    При покупке билета вам необходимо сообщить
                    ваш номер телефона кассиру.<br>
                    <strong>Внимание! Бронирование билетов производится не менее чем за сутки! Забронированные билеты
                        необходимо выкупить в кассе дельфинария не позднее 30 минут до начала представления!</strong>
                </p>
                <div class="close_window"><a href="/">
                        Хорошо</a>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            var bookings = JSON.parse($('#bookings').val());
            var months = {
                'Январь': 0,
                'Февраль': 1,
                'Март': 2,
                'Апрель': 3,
                'Май': 4,
                'Июнь': 5,
                'Июль': 6,
                'Август': 7,
                'Сентябрь': 8,
                'Октябрь': 9,
                'Ноябрь': 10,
                'Декабрь': 11
            };
            <?php
            $formatAllTimes = [];
            foreach($allTimes as $time) {
                $formatAllTimes[] = "'" . $time . "'";
            }
            ?>
            var times = [
                <?php echo implode(',', $formatAllTimes); ?>
            ];
            var relations = {
                '19:00': [
                    2, 4, 5, 0
                ],
                '21:00': [
                    3, 6
                ]
            };

            var types = {
                'Общение с дельфинами': 0,
                'Шоу-программа': 1
            };

            filterDates();
            switchTypes();
            calcPrice();

            $('#field_4').change(function () {
                filterDates();
            });

//        $('#field_2').change(function () {
//            filterDates();
//        });

            $('#field_3').change(function () {
                filterDates(1);
            });

            $('#field_7').change(function () {
                switchTypes();
            });

            $('#frmFormMail select').change(function () {
                calcPrice();
            });

            function calcPrice() {
                var type = $('#field_7').val();
                var total_price = 0;
                if (type in types) {
                    var selector = '';
                    if (types[type] > 0) {
                        selector = 'show';
                    } else {
                        selector = 'dolphin';
                    }
                    $('#' + selector + ' select').each(function () {
                        var e = $(this);
                        var price = e.parents('.field_block').attr('price');
                        var qty = e.find('option:selected').val();
                        total_price += parseInt((parseFloat(qty) || 0) * (parseFloat(price) || 0));
                    });
                }
                $('#price').html(total_price);
            }

            function switchTypes() {
                var type = $('#field_7').val();
                if (type in types) {
                    if (types[type] > 0) {
                        $('#show').show();
                        $('#dolphin').hide();
                    } else {
                        $('#show').hide();
                        $('#dolphin').show();
                    }
                }
            }

            function filterDates(muteDays) {
                var now = new Date();
                var currentMonth = months[$('#field_4').val()];
                var year = now.getFullYear();
                if(currentMonth < 10) {
                    year = year + 1;
                }
                var dateTo = new Date(year, currentMonth + 1, 0);
                var timeField = $('#field_2');
                var dayField = $('#field_3');

                var currentM = (currentMonth.length > 1) ? ('0' + (currentMonth + 1)).slice(-2) : ('0' + (currentMonth + 1));
                var selectedDate = year + '-' + currentM + '-' + dayField.val();

                timeField.find('option').remove();
                var cont = true;
                if(bookings[selectedDate]) {
                    if(bookings[selectedDate].length == 1 && bookings[selectedDate][0] == 'Отмена бронирования' ) {
                        cont = false;
                    } else {
                        bookings[selectedDate].forEach(function(item) {
                            timeField.append('<option value="' + item + '">' + item + '</option>')
                        });
                    }
                } else {
                    times.forEach(function(item){
                        timeField.append('<option value="' + item + '">' + item + '</option>')
                    });
                }

                if(!muteDays) {
                    dayField.find('option').remove();
                }

                var rel = [];
                if (timeField.val() in relations) {
                    var rel = relations[timeField.val()];
                }
                var disabledCurrentDate = false;
                if(!muteDays) {
                    for (var d = new Date(year, currentMonth, 1); d <= dateTo; d.setDate(d.getDate() + 1)) {

                        var currentDate = new Date(d);

                        function pad(n) {
                            return n < 10 ? '0' + n : n
                        }

                        function in_array(needle, haystack) {
                            var found = 0;
                            for (var i = 0, len = haystack.length; i < len; i++) {
                                if (haystack[i] == needle) return i;
                                found++;
                            }
                            return -1;
                        }

                        var dayOption = pad(currentDate.getDate());
                        var currentM = (currentMonth.length > 1) ? ('0' + (currentMonth + 1)).slice(-2) : ('0' + (currentMonth + 1));
                        var dat = currentDate.getFullYear() + '-' + currentM + '-' + ('0' + currentDate.getDate()).slice(-2);

                        var cancelDay = false;
                        if(bookings[dat]) {
                            bookings[dat].forEach(function(item) {
                                if(item == 'Отмена бронирования') {
                                    cancelDay = true;
                                }
                            });
                        }

                        if(cancelDay && currentDate.getDate() == now.getDate()) {
                            disabledCurrentDate = true;
                        }
                        if(!cancelDay) {
                            var selected = '';
                            if(disabledCurrentDate && dayOption > now.getDate()) {
                                selected = 'selected';
                                disabledCurrentDate = false;

                                if(bookings[dat]) {
                                    bookings[dat].forEach(function(item) {
                                        timeField.append('<option value="' + item + '">' + item + '</option>')
                                    });
                                } else {
                                    times.forEach(function(item){
                                        timeField.append('<option value="' + item + '">' + item + '</option>')
                                    });
                                }
                            }
                            if (rel.length) {
                                if (in_array(currentDate.getDay(), rel) != -1) {
                                    dayField.append('<option value="' + dayOption + '"' + selected + '>' + dayOption + '</option>')
                                }
                            } else {
                                dayField.append('<option value="' + dayOption + '"' + selected + '>' + dayOption + '</option>')
                            }
                        }
                    }
                }
            }
        });
    </script>
<?php phpfmg_javascript($sErr); ?>