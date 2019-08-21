<?php
defined('_JEXEC') or die;
?>
<?php

// if the from is loaded from WordPress form loader plugin,
// the phpfmg_display_form() will be called by the loader
if( !defined('FormmailMakerFormLoader') ){
    # This block must be placed at the very top of page.
    # --------------------------------------------------
	require_once( dirname(__FILE__).'/form.lib.php' );
    phpfmg_display_form();
    # --------------------------------------------------
};


function phpfmg_form( $sErr = false ){
		$style=" class='form_text' ";

?>




<div id='frmFormMailContainer' class="reserve_block">
<div class="title_reserve">
    <a href="#">
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
	<label class='form_field'><span>Ваше ФИО</span>
        <input type="text" placeholder='Иванов Иван Иванович' name="field_0"  id="field_0" value="<?php  phpfmg_hsc("field_0", ""); ?>" class='text_box'>
    </label>   
	<div id='field_0_tip' class='instruction'></div>

</li>

<li class='field_block' id='field_1_div'> 
	<label class='form_field'><span>Номер телефона</span> 
        <input placeholder='+7 (123) 456 78 90' type="text" name="field_1"  id="field_1" value="<?php  phpfmg_hsc("field_1", ""); ?>" class='text_box'>
    </label>   	
	<div id='field_1_tip' class='instruction'></div>
</li>

<li class='field_block' id='field_2_div'>
	<label class='form_field'><span>Дата представления</span> </label> 
	<label class='form_field'>
<?php
    $field_2 = array(
        'month' => "Месяц =,|Января|Февраля|Марта|Апреля|Мая|Июня|Июля|Августа|Сентября|Октября|Ноября|Декабря",
        'day' => "День =,|01|02|03|04|05|06|07|08|09|10|11|12|13|14|15|16|17|18|19|20|21|22|23|24|25|26|27|28|29|30|31",
        'format' => "dd/mm",
        'separator' => "/",
        'field_name' => "field_2",
    );
    phpfmg_date_dropdown( $field_2 );
?>
    </label> 

	<div id='field_2_tip' class='instruction'></div>

</li>

<li class='field_block' id='field_3_div'>
	<label class='form_field'><span>Время</span></label> 
	<label class='form_field'>
<?php
    $field_3 = array(
        'hour' => "Час=,default|09|10|11|12|13|14|15|16|17|18|19|20|21",
        'hourOpt' => "h24",
        'minute' => "Мин=,default|00|30",
        'amfm' => "=,default|AM|PM",
        'second' => "",
        'field_name' => "field_3",
    );
    phpfmg_time_dropdown( $field_3 );
?>
    </label>
	<div id='field_3_tip' class='instruction'></div>

</li>


<li class='field_block' id='phpfmg_captcha_div'>
	<?php phpfmg_show_captcha(); ?>
</li>


            <li>
	
                <div class="bottom_reserv">
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
                ваш номер телефона кассиру.
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