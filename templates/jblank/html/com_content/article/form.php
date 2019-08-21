<?php
defined('_JEXEC') or die;
?>
<?php

if( !defined('FormmailMakerFormLoader') ){

	require_once( dirname(__FILE__).'/form.lib.php' );
    phpfmg_display_form();

};


function phpfmg_form( $sErr = false ){
		$style=" class='form_text' ";

?>




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

<form name="frmFormMail" id="frmFormMail" target="submitToFrame" action='<?php echo PHPFMG_ADMIN_URL . '' ; ?>' method='post' enctype='multipart/form-data' onsubmit='return fmgHandler.onSubmit(this);'>

<input type='hidden' name='formmail_submit' value='Y'>
<input type='hidden' name='mod' value='ajax'>
<input type='hidden' name='func' value='submit'>


<ol class='phpfmg_form' >

<li class='field_block' id='field_0_div'>

	<label class='form_field'>
        <span>Ваше ФИО</span>
        <input placeholder='Иванов Иван Иванович' type="text" name="field_0"  id="field_0" value="<?php  phpfmg_hsc("field_0", ""); ?>" class='text_box'>
    </label>
	<div id='field_0_tip' class='instruction'></div>
</li>

<li class='field_block' id='field_1_div'>
	<label class='form_field'>
        <span>Номер телефона</span>
        <input type="text" name="field_1"  id="field_1" value="<?php  phpfmg_hsc("field_1", ""); ?>" class='text_box'>
    </label>
	<div id='field_1_tip' class='instruction'></div>
</li>

<li class='field_block' id='field_2_div'>
	<label class='form_field'>
        <span>Время представления<br>*Время и дата должны соответствовать актуальному расписанию. Расписание в верхнем правом углу экрана на желтой плашке!</span>
        <?php phpfmg_dropdown( 'field_2', "11:00|16:00|19:00|21:00" );?>
    </label>
	<div id='field_2_tip' class='instruction'></div>
</li>

<li class='field_block' id='field_3_div'>
	<label class='form_field'>
        <span>День</span>
        <?php phpfmg_dropdown( 'field_3', "01|02|03|04|05|06|07|08|09|10|11|12|13|14|15|16|17|18|19|20|21|22|23|24|25|26|27|28|29|30|31" );?>
	</label>
    <div id='field_3_tip' class='instruction'></div>
</li>

<li class='field_block' id='field_4_div'>
	<label class='form_field'>
        <span>Месяц</span>
	<?php phpfmg_dropdown( 'field_4', "Июнь|Июль|Август|Сентябрь|Октябрь" );?>
    </label>
	<div id='field_4_tip' class='instruction'></div>
</li>

<li class='field_block' id='field_5_div'>
	<label class='form_field'>
        <span>Кол-во билетов для взрослых</span>
        <?php phpfmg_dropdown( 'field_5', "0|1=1,default|2|3|4|5|6|7|8|9|10|11|12|13|14|15|16|17|18|19|20|&gt;20" );?>
    </label>
	<div id='field_5_tip' class='instruction'></div>
</li>

<li class='field_block' id='field_6_div'>
	<label class='form_field'>
        <span>Кол-во билетов для детей</span>
        <?php phpfmg_dropdown( 'field_6', "0|1|2|3|4|5|6|7|8|9|10|11|12|13|14|15|16|17|18|19|20|&gt;20" );?>
    </label>
	<div id='field_6_tip' class='instruction'></div>
</li>

<li class='field_block' id='field_7_div'>
	<label class='form_field form_field_checkbox'>
        <?php phpfmg_checkboxes( 'field_7', '=1');?>
        <span>Согласен с условиями <a href="/soglasheniye.html" target="_blank">пользовательского соглашения</a></span>
    </label>
	<div id='field_0_tip' class='instruction'></div>
</li>

<li class='field_block' id='phpfmg_captcha_div'>
	<?php phpfmg_show_captcha(); ?>
</li>

<li>

<div class="bottom_reserv">
    <label class="form_field">
        <span>Внимание! Бронирование билетов производится за сутки! Забронированные билеты необходимо выкупить в кассе дельфинария не позднее, чем за 30 минут до начала представления!</span>
    </label>
    <input type='submit' value='Забронировать билет' class='form_button'>
</div>

    <div id='err_required' class="form_error" style='display:none;'>
        <label class='form_error_title'>Все поля обязательны. Заполните их, пож-та!</label>
    </div>



    <span id='phpfmg_processing' style='display:none;'>
        <img id='phpfmg_processing_gif' src='<?php echo PHPFMG_ADMIN_URL . '?mod=image&amp;func=processing' ;?>' border=0 alt='Processing...'> <label id='phpfmg_processing_dots'></label>
    </span>
</li>

</ol>
</form>

<iframe name="submitToFrame" id="submitToFrame" src="javascript:false" style="position:absolute;top:-10000px;left:-10000px;" /></iframe>

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
  <strong>Внимание! Бронирование билетов производится не менее чем за сутки! Забронированные билеты необходимо выкупить в кассе дельфинария не позднее 30 минут до начала представления!</strong>
            </p>
            <div class="close_window"><a href="/">
                Хорошо</a>
            </div>
        </div>
    </div>
</div>
<?php

    phpfmg_javascript($sErr);

}
function phpfmg_form_css(){
    $formOnly = isset($GLOBALS['formOnly']) && true === $GLOBALS['formOnly'];
?>

<?php
}
?>