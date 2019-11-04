<?php
defined('_JEXEC') or die;

if(isset($_GET['items'])) {
    $items = json_decode($_GET['items']);

    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    $query->delete($db->quoteName('#__booking'));
    $db->setQuery($query);

    $result = $db->query();
    foreach($items as $item) {
        $date = new stdClass();
        $date->title = $item->title;
        $date->start = $item->date;
        JFactory::getDbo()->insertObject('#__booking', $date, 'id');
    }
    echo 'success';
}
$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('*')->from($db->quoteName('#__booking'))->group('start, title');
$db->setQuery($query);
$bookings = $db->loadObjectList();
?>
<script src="https://code.jquery.com/jquery-1.11.3.js"></script>
<script type="text/javascript">
    var $jq = jQuery.noConflict();
</script>
<link href='/media/fullcalendar.min.css' rel='stylesheet' />
<link href='/media/fullcalendar.print.min.css' rel='stylesheet' media='print' />
<script src='/media/lib/moment.min.js'></script>
<script src='/media/lib/jquery.min.js'></script>
<script src='/media/lib/jquery-ui.min.js'></script>
<script src='/media/fullcalendar.min.js'></script>
<script>
    jQuery(document).ready(function($){
        $('#external-events .fc-event').each(function() {

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
            }, function(response){
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
            drop: function() {
                syncToServer();
            },
            eventClick: function(calEvent, jsEvent, view)
            {
                if (confirm('Delete "' + calEvent.title + '"?'))
                {
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
        font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
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

    <div id='external-events'>
        <h4>Время</h4>
        <div class='fc-event'>11:00</div>
        <div class='fc-event'>16:00</div>
        <div class='fc-event'>19:00</div>
        <div class='fc-event'>21:00</div>
        <div class='fc-event'>Отмена бронирования</div>
        <p>
            <input type='checkbox' id='drop-remove' />
            <label for='drop-remove'>remove after drop</label>
        </p>
    </div>

    <div id='calendar'></div>

    <div style='clear:both'></div>

</div>