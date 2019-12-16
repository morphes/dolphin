<?php
defined('_JEXEC') or die;
$db = JFactory::getDbo();
if (isset($_GET['type']) && $_GET['type'] == 'saveschedule') {
    if (isset($_GET['time']) && $times = $_GET['time']) {
        $query = $db->getQuery(true);
        $query->delete($db->quoteName('#__time'));
        $db->setQuery($query);

        $result = $db->query();
        foreach ($times as $time) {
            if ($time) {
                $item       = new stdClass();
                $item->time = $time;
                JFactory::getDbo()->insertObject('#__time', $item, 'id');
            }
        }
        header("Location: /administrator/index.php?option=com_payment");
    }
}
if (isset($_GET['type']) && $_GET['type'] == 'schedule') { ?>
    <form action="/administrator/index.php">
        <input type="hidden" name="option" value="com_payment"/>
        <input type="hidden" name="type" value="saveschedule"/>
        <?php
        $query = $db->getQuery(true);
        $query->select('*')->from($db->quoteName('#__time'))->group('time');
        $db->setQuery($query);
        $times = $db->loadObjectList();
        ?>
        <?php for ($i = 1; $i < 11; $i++) { ?>
            <div class="control-group">
                <div class="control-label">
                    <label id="jform_version_note-lbl" for="jform_version_note" class="hasTooltip" title=""
                           data-original-title="">
                        <?php echo $i; ?>-ая запись
                    </label>
                </div>
                <div class="controls">
                    <input type="text" name="time[<?php echo $i; ?>]" id="jform_version_note" class="span12"
                           size="45" maxlength="255" aria-invalid="false"
                        <?php if (isset($times[$i - 1])) { ?>
                            value="<?php echo $times[$i - 1]->time; ?>"
                        <?php } ?>
                    >
                </div>
            </div>
        <?php } ?>
        <input type="submit" class="btn hasTooltip js-stools-btn-clear" title=""
               data-original-title="Отправить">
    </form>
<?php
} elseif (count($_POST) && isset($_POST['type']) && $_POST['type'] == 'prices') {
    $updatedFields = [
        'price',
        'description'
    ];
    $fields = [];
    foreach($_POST as $name => $postField) {
        if(in_array($name, $updatedFields)) {
            $fields[] = $name . ' = "'. $postField . '"';
        }
    }
    $conditions = array(
        $db->quoteName('id') . ' = ' . $_POST['id']
    );
    $query = $db->getQuery(true);
    $query->update($db->quoteName('#__tickets'))->set($fields)->where($conditions);
    $db->setQuery($query);
    $result = $db->execute();
    header("Location: /administrator/index.php?option=com_payment&type=prices");
} elseif (isset($_GET['type']) && $_GET['type'] == 'prices' && isset($_GET['id'])) {
    $query = $db->getQuery(true);
    $query->select('*')->from($db->quoteName('#__tickets'))->where($db->quoteName('id') . ' = ' . $_GET['id']);
    $db->setQuery($query);
    $ticket = $db->loadAssoc();
?>
    <?php $fields = [
            'description' => 'Наименование',
            'name' => 'Техническое название',
            'price' => 'Цена',
            'api_id' => 'ID для интеграции с Лаймом'
    ]; ?>
    <form action="/administrator/index.php" method="POST">
        <input type="hidden" name="option" value="com_payment"/>
        <input type="hidden" name="type" value="prices"/>
        <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>"/>
        <?php foreach($fields as $fieldName => $fieldDescription) { ?>
            <div class="control-label">
                <label id="jform_version_note-lbl" for="jform_version_note" class="hasTooltip" title=""
                       data-original-title="">
                    <?php echo $fieldDescription; ?>
                </label>
            </div>
            <div class="controls">
                <input type="text" name="<?php echo $fieldName; ?>" id="jform_version_note" class="span12"
                       size="45" maxlength="255" aria-invalid="false"
                    value="<?php if(isset($ticket[$fieldName])) { echo $ticket[$fieldName]; } ?>"
                       <?php if(in_array($fieldName, ['api_id', 'name'])) { echo 'disabled'; } ?>
                >
            </div>
        <?php } ?>
            <input type="submit" class="btn hasTooltip js-stools-btn-clear" title=""
                    data-original-title="Отправить">
    </form>
<?php } elseif (isset($_GET['type']) && $_GET['type'] == 'prices') {
    $query = $db->getQuery(true);
    $query->select('*')->from($db->quoteName('#__tickets'));
    $db->setQuery($query);
    $tickets = $db->loadObjectList(); ?>
    <table class="table table-striped" id="articleList">
        <thead>
        <tr>
            <th width="1%" class="nowrap">
                ID
            </th>
            <th>
                Наименование
            </th>
            <th class="nowrap">
                Стоимость
            </th>
            <th width="10%" class="nowrap hidden-phone">
                Описание
            </th>
        </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="10">
                </td>
            </tr>
        </tfoot>
        <tbody>
            <?php foreach($tickets as $ticket) { ?>
                <tr>
                    <td>
                        <?php echo $ticket->id; ?>
                    </td>
                    <td>
                        <a href="/administrator/index.php?option=com_payment&type=prices&id=<?php echo $ticket->id; ?>"><?php echo $ticket->description; ?></a>
                    </td>
                    <td>
                        <?php echo $ticket->price; ?>
                    </td>
                    <td>
                        <?php echo $ticket->name; ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } else {
    if (isset($_GET['items'])) {
        $items = json_decode($_GET['items']);

        $db    = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->delete($db->quoteName('#__booking'));
        $db->setQuery($query);

        $result = $db->query();
        foreach ($items as $item) {
            $date        = new stdClass();
            $date->title = $item->title;
            $time = strtotime($item->date);
            $newformat = date('Y-m-d',$time);
            $date->start = $newformat;
            JFactory::getDbo()->insertObject('#__booking', $date, 'id');
        }
        echo 'success';
    }
    $db    = JFactory::getDbo();
    $query = $db->getQuery(true);
    $query->select('*')->from($db->quoteName('#__booking'))->group('start, title');
    $db->setQuery($query);
    $bookings = $db->loadObjectList();
    ?>
    <script src="https://code.jquery.com/jquery-1.11.3.js"></script>
    <script type="text/javascript">
        var $jq = jQuery.noConflict();
    </script>
    <link href='/media/fullcalendar.min.css' rel='stylesheet'/>
    <link href='/media/fullcalendar.print.min.css' rel='stylesheet' media='print'/>
    <script src='/media/lib/moment.min.js'></script>
    <script src='/media/lib/jquery.min.js'></script>
    <script src='/media/lib/jquery-ui.min.js'></script>
    <script src='/media/fullcalendar.min.js'></script>
    <script>
        jQuery(document).ready(function ($) {
            $('#external-events .fc-event').each(function () {

                $(this).data('event', {
                    title: $.trim($(this).text()),
                    stick: true
                });

                $(this).draggable({
                    zIndex: 999,
                    revert: true,
                    revertDuration: 0
                });

            });

            function syncToServer() {
                var items = [];
                $('#calendar').fullCalendar('clientEvents').forEach(function (item, i, data) {
                        var date = item.start._d;
                        items.push(
                            {
                                title: item.title,
                                date: date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + ('0' + date.getDate()).slice(-2)
                            }
                        );
                    }
                );
                $.get('/administrator/index.php', {
                    items: JSON.stringify(items),
                    option: 'com_payment'
                }, function (response) {
                    console.log(response);
                });
            }

            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                },
                editable: true,
                droppable: true,
                events: [
                    <?php foreach($bookings as $booking) { ?>
                    {
                        id: '<?php echo $booking->id; ?>',
                        title: '<?php echo $booking->title; ?>',
                        start: '<?php echo $booking->start; ?>'
                    },
                    <?php } ?>
                ],
                drop: function () {
                    syncToServer();
                },
                eventClick: function (calEvent, jsEvent, view) {
                    if (confirm('Delete "' + calEvent.title + '"?')) {
                        $('#calendar').fullCalendar('removeEvents', calEvent._id);
                    }
                    syncToServer();
                },
            });
        });
    </script>
    <style>

        body {
            margin-top: 40px;
            text-align: center;
            font-size: 14px;
            font-family: "Lucida Grande", Helvetica, Arial, Verdana, sans-serif;
        }

        #wrap {
            width: 1100px;
            margin: 0 auto;
        }

        #external-events {
            float: left;
            width: 150px;
            padding: 0 10px;
            border: 1px solid #ccc;
            background: #eee;
            text-align: left;
        }

        #external-events h4 {
            font-size: 16px;
            margin-top: 0;
            padding-top: 1em;
        }

        #external-events .fc-event {
            margin: 10px 0;
            cursor: pointer;
        }

        #external-events p {
            margin: 1.5em 0;
            font-size: 11px;
            color: #666;
        }

        #external-events p input {
            margin: 0;
            vertical-align: middle;
        }

        #calendar {
            float: right;
            width: 900px;
        }

        #drop-remove, label[for=drop-remove] {
            display: none;
        }

        .cancel-event {
            margin: 10px 0;
            cursor: pointer;
            position: relative;
            background-color: darkred;
            color: white;
            display: block;
            font-size: .85em;
            line-height: 1.3;
            border-radius: 3px;
            border: 1px solid #3a87ad;
        }

    </style>

    <div id='wrap'>

        <div class="btn-wrapper">
            <a href="/administrator/index.php?option=com_payment&type=schedule">
                <button type="button" class="btn hasTooltip js-stools-btn-clear" title=""
                        data-original-title="Расписание">
                    Управление расписанием
                </button>
            </a>
            <a href="/administrator/index.php?option=com_payment&type=prices">
                <button type="button" class="btn hasTooltip js-stools-btn-clear" title=""
                        data-original-title="Цены">
                    Управление билетами
                </button>
            </a>
        </div>

        <div class="btn-wrapper">
            <a href="/administrator/index.php?option=com_payment&type=schedule">

            </a>
        </div>

        <div id='external-events'>
            <h4>Время</h4>
            <?php
            $db    = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('*')->from($db->quoteName('#__time'))->group('time');
            $db->setQuery($query);
            $times = $db->loadObjectList();
            ?>
            <?php foreach ($times as $time) { ?>
                <?php if ($time->time) { ?>
                    <div class='fc-event'><?php echo $time->time; ?></div>
                <?php } ?>
            <?php } ?>
            <div class='fc-event'>Отмена бронирования</div>
            <p>
                <input type='checkbox' id='drop-remove'/>
                <label for='drop-remove'>remove after drop</label>
            </p>
        </div>

        <div id='calendar'></div>

        <div style='clear:both'></div>

    </div>
<?php }
