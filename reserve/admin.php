<?php
require_once( dirname(__FILE__).'/form.lib.php' );

define( 'PHPFMG_USER', "nikolayblinov@yandex.ru" ); // must be a email address. for sending password to you.
define( 'PHPFMG_PW', "a2ec43" );

?>
<?php
/**
 * GNU Library or Lesser General Public License version 2.0 (LGPLv2)
*/

# main
# ------------------------------------------------------
error_reporting( E_ERROR ) ;
phpfmg_admin_main();
# ------------------------------------------------------




function phpfmg_admin_main(){
    $mod  = isset($_REQUEST['mod'])  ? $_REQUEST['mod']  : '';
    $func = isset($_REQUEST['func']) ? $_REQUEST['func'] : '';
    $function = "phpfmg_{$mod}_{$func}";
    if( !function_exists($function) ){
        phpfmg_admin_default();
        exit;
    };

    // no login required modules
    $public_modules   = false !== strpos('|captcha|', "|{$mod}|", "|ajax|");
    $public_functions = false !== strpos('|phpfmg_ajax_submit||phpfmg_mail_request_password||phpfmg_filman_download||phpfmg_image_processing||phpfmg_dd_lookup|', "|{$function}|") ;   
    if( $public_modules || $public_functions ) { 
        $function();
        exit;
    };
    
    return phpfmg_user_isLogin() ? $function() : phpfmg_admin_default();
}

function phpfmg_ajax_submit(){
    $phpfmg_send = phpfmg_sendmail( $GLOBALS['form_mail'] );
    $isHideForm  = isset($phpfmg_send['isHideForm']) ? $phpfmg_send['isHideForm'] : false;

    $response = array(
        'ok' => $isHideForm,
        'error_fields' => isset($phpfmg_send['error']) ? $phpfmg_send['error']['fields'] : '',
        'OneEntry' => isset($GLOBALS['OneEntry']) ? $GLOBALS['OneEntry'] : '',
    );
    
    @header("Content-Type:text/html; charset=$charset");
    echo "<html><body><script>
    var response = " . json_encode( $response ) . ";
    try{
        parent.fmgHandler.onResponse( response );
    }catch(E){};
    \n\n";
    echo "\n\n</script></body></html>";

}


function phpfmg_admin_default(){
    if( phpfmg_user_login() ){
        phpfmg_admin_panel();
    };
}



function phpfmg_admin_panel()
{    
    phpfmg_admin_header();
    phpfmg_writable_check();
?>    
<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td valign=top style="padding-left:280px;">

<style type="text/css">
    .fmg_title{
        font-size: 16px;
        font-weight: bold;
        padding: 10px;
    }
    
    .fmg_sep{
        width:32px;
    }
    
    .fmg_text{
        line-height: 150%;
        vertical-align: top;
        padding-left:28px;
    }

</style>

<script type="text/javascript">
    function deleteAll(n){
        if( confirm("Are you sure you want to delete?" ) ){
            location.href = "admin.php?mod=log&func=delete&file=" + n ;
        };
        return false ;
    }
</script>


<div class="fmg_title">
    1. Email Traffics
</div>
<div class="fmg_text">
    <a href="admin.php?mod=log&func=view&file=1">view</a> &nbsp;&nbsp;
    <a href="admin.php?mod=log&func=download&file=1">download</a> &nbsp;&nbsp;
    <?php 
        if( file_exists(PHPFMG_EMAILS_LOGFILE) ){
            echo '<a href="#" onclick="return deleteAll(1);">delete all</a>';
        };
    ?>
</div>


<div class="fmg_title">
    2. Form Data
</div>
<div class="fmg_text">
    <a href="admin.php?mod=log&func=view&file=2">view</a> &nbsp;&nbsp;
    <a href="admin.php?mod=log&func=download&file=2">download</a> &nbsp;&nbsp;
    <?php 
        if( file_exists(PHPFMG_SAVE_FILE) ){
            echo '<a href="#" onclick="return deleteAll(2);">delete all</a>';
        };
    ?>
</div>

<div class="fmg_title">
    3. Form Generator
</div>
<div class="fmg_text">
    <a href="http://www.formmail-maker.com/generator.php" onclick="document.frmFormMail.submit(); return false;" title="<?php echo htmlspecialchars(PHPFMG_SUBJECT);?>">Edit Form</a> &nbsp;&nbsp;
    <a href="http://www.formmail-maker.com/generator.php" >New Form</a>
</div>
    <form name="frmFormMail" action='http://www.formmail-maker.com/generator.php' method='post' enctype='multipart/form-data'>
    <input type="hidden" name="uuid" value="<?php echo PHPFMG_ID; ?>">
    <input type="hidden" name="external_ini" value="<?php echo function_exists('phpfmg_formini') ?  phpfmg_formini() : ""; ?>">
    </form>

		</td>
	</tr>
</table>

<?php
    phpfmg_admin_footer();
}



function phpfmg_admin_header( $title = '' ){
    header( "Content-Type: text/html; charset=" . PHPFMG_CHARSET );
?>
<html>
<head>
    <title><?php echo '' == $title ? '' : $title . ' | ' ; ?>PHP FormMail Admin Panel </title>
    <meta name="keywords" content="PHP FormMail Generator, PHP HTML form, send html email with attachment, PHP web form,  Free Form, Form Builder, Form Creator, phpFormMailGen, Customized Web Forms, phpFormMailGenerator,formmail.php, formmail.pl, formMail Generator, ASP Formmail, ASP form, PHP Form, Generator, phpFormGen, phpFormGenerator, anti-spam, web hosting">
    <meta name="description" content="PHP formMail Generator - A tool to ceate ready-to-use web forms in a flash. Validating form with CAPTCHA security image, send html email with attachments, send auto response email copy, log email traffics, save and download form data in Excel. ">
    <meta name="generator" content="PHP Mail Form Generator, phpfmg.sourceforge.net">

    <style type='text/css'>
    body, td, label, div, span{
        font-family : Verdana, Arial, Helvetica, sans-serif;
        font-size : 12px;
    }
    </style>
</head>
<body  marginheight="0" marginwidth="0" leftmargin="0" topmargin="0">

<table cellspacing=0 cellpadding=0 border=0 width="100%">
    <td nowrap align=center style="background-color:#024e7b;padding:10px;font-size:18px;color:#ffffff;font-weight:bold;width:250px;" >
        Form Admin Panel
    </td>
    <td style="padding-left:30px;background-color:#86BC1B;width:100%;font-weight:bold;" >
        &nbsp;
<?php
    if( phpfmg_user_isLogin() ){
        echo '<a href="admin.php" style="color:#ffffff;">Main Menu</a> &nbsp;&nbsp;' ;
        echo '<a href="admin.php?mod=user&func=logout" style="color:#ffffff;">Logout</a>' ;
    }; 
?>
    </td>
</table>

<div style="padding-top:28px;">

<?php
    
}


function phpfmg_admin_footer(){
?>

</div>

<div style="color:#cccccc;text-decoration:none;padding:18px;font-weight:bold;">
	:: <a href="http://phpfmg.sourceforge.net" target="_blank" title="Free Mailform Maker: Create read-to-use Web Forms in a flash. Including validating form with CAPTCHA security image, send html email with attachments, send auto response email copy, log email traffics, save and download form data in Excel. " style="color:#cccccc;font-weight:bold;text-decoration:none;">PHP FormMail Generator</a> ::
</div>

</body>
</html>
<?php
}


function phpfmg_image_processing(){
    $img = new phpfmgImage();
    $img->out_processing_gif();
}


# phpfmg module : captcha
# ------------------------------------------------------
function phpfmg_captcha_get(){
    $img = new phpfmgImage();
    $img->out();
    //$_SESSION[PHPFMG_ID.'fmgCaptchCode'] = $img->text ;
    $_SESSION[ phpfmg_captcha_name() ] = $img->text ;
}



function phpfmg_captcha_generate_images(){
    for( $i = 0; $i < 50; $i ++ ){
        $file = "$i.png";
        $img = new phpfmgImage();
        $img->out($file);
        $data = base64_encode( file_get_contents($file) );
        echo "'{$img->text}' => '{$data}',\n" ;
        unlink( $file );
    };
}


function phpfmg_dd_lookup(){
    $paraOk = ( isset($_REQUEST['n']) && isset($_REQUEST['lookup']) && isset($_REQUEST['field_name']) );
    if( !$paraOk )
        return;
        
    $base64 = phpfmg_dependent_dropdown_data();
    $data = @unserialize( base64_decode($base64) );
    if( !is_array($data) ){
        return ;
    };
    
    
    foreach( $data as $field ){
        if( $field['name'] == $_REQUEST['field_name'] ){
            $nColumn = intval($_REQUEST['n']);
            $lookup  = $_REQUEST['lookup']; // $lookup is an array
            $dd      = new DependantDropdown(); 
            echo $dd->lookupFieldColumn( $field, $nColumn, $lookup );
            return;
        };
    };
    
    return;
}


function phpfmg_filman_download(){
    if( !isset($_REQUEST['filelink']) )
        return ;
        
    $info =  @unserialize(base64_decode($_REQUEST['filelink']));
    if( !isset($info['recordID']) ){
        return ;
    };
    
    $file = PHPFMG_SAVE_ATTACHMENTS_DIR . $info['recordID'] . '-' . $info['filename'];
    phpfmg_util_download( $file, $info['filename'] );
}


class phpfmgDataManager
{
    var $dataFile = '';
    var $columns = '';
    var $records = '';
    
    function phpfmgDataManager(){
        $this->dataFile = PHPFMG_SAVE_FILE; 
    }
    
    function parseFile(){
        $fp = @fopen($this->dataFile, 'rb');
        if( !$fp ) return false;
        
        $i = 0 ;
        $phpExitLine = 1; // first line is php code
        $colsLine = 2 ; // second line is column headers
        $this->columns = array();
        $this->records = array();
        $sep = chr(0x09);
        while( !feof($fp) ) { 
            $line = fgets($fp);
            $line = trim($line);
            if( empty($line) ) continue;
            $line = $this->line2display($line);
            $i ++ ;
            switch( $i ){
                case $phpExitLine:
                    continue;
                    break;
                case $colsLine :
                    $this->columns = explode($sep,$line);
                    break;
                default:
                    $this->records[] = explode( $sep, phpfmg_data2record( $line, false ) );
            };
        }; 
        fclose ($fp);
    }
    
    function displayRecords(){
        $this->parseFile();
        echo "<table border=1 style='width=95%;border-collapse: collapse;border-color:#cccccc;' >";
        echo "<tr><td>&nbsp;</td><td><b>" . join( "</b></td><td>&nbsp;<b>", $this->columns ) . "</b></td></tr>\n";
        $i = 1;
        foreach( $this->records as $r ){
            echo "<tr><td align=right>{$i}&nbsp;</td><td>" . join( "</td><td>&nbsp;", $r ) . "</td></tr>\n";
            $i++;
        };
        echo "</table>\n";
    }
    
    function line2display( $line ){
        $line = str_replace( array('"' . chr(0x09) . '"', '""'),  array(chr(0x09),'"'),  $line );
        $line = substr( $line, 1, -1 ); // chop first " and last "
        return $line;
    }
    
}
# end of class



# ------------------------------------------------------
class phpfmgImage
{
    var $im = null;
    var $width = 73 ;
    var $height = 33 ;
    var $text = '' ; 
    var $line_distance = 8;
    var $text_len = 4 ;

    function phpfmgImage( $text = '', $len = 4 ){
        $this->text_len = $len ;
        $this->text = '' == $text ? $this->uniqid( $this->text_len ) : $text ;
        $this->text = strtoupper( substr( $this->text, 0, $this->text_len ) );
    }
    
    function create(){
        $this->im = imagecreate( $this->width, $this->height );
        $bgcolor   = imagecolorallocate($this->im, 255, 255, 255);
        $textcolor = imagecolorallocate($this->im, 0, 0, 0);
        $this->drawLines();
        imagestring($this->im, 5, 20, 9, $this->text, $textcolor);
    }
    
    function drawLines(){
        $linecolor = imagecolorallocate($this->im, 210, 210, 210);
    
        //vertical lines
        for($x = 0; $x < $this->width; $x += $this->line_distance) {
          imageline($this->im, $x, 0, $x, $this->height, $linecolor);
        };
    
        //horizontal lines
        for($y = 0; $y < $this->height; $y += $this->line_distance) {
          imageline($this->im, 0, $y, $this->width, $y, $linecolor);
        };
    }
    
    function out( $filename = '' ){
        if( function_exists('imageline') ){
            $this->create();
            if( '' == $filename ) header("Content-type: image/png");
            ( '' == $filename ) ? imagepng( $this->im ) : imagepng( $this->im, $filename );
            imagedestroy( $this->im ); 
        }else{
            $this->out_predefined_image(); 
        };
    }

    function uniqid( $len = 0 ){
        $md5 = md5( uniqid(rand()) );
        return $len > 0 ? substr($md5,0,$len) : $md5 ;
    }
    
    function out_predefined_image(){
        header("Content-type: image/png");
        $data = $this->getImage(); 
        echo base64_decode($data);
    }
    
    // Use predefined captcha random images if web server doens't have GD graphics library installed  
    function getImage(){
        $images = array(
			'E72B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7QkNEQx1CGUMdkMQCGhgaHR0dHQLQxFwbAh1EUMVaGYBiAUjuC41aNW3VyszQLCT3AeUDGFoZ0cxjdGCYwohmHitQJbqYCFAlqt7QEJEG1tBAFDcPVPhREWJxHwAWO8vla4XJkQAAAABJRU5ErkJggg==',
			'E039' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7QkMYAhhDGaY6IIkFNDCGsDY6BASgiLG2MjQEOoigiIk0OjQ6wsTATgqNmrYya+qqqDAk90HUOUzF0AsiMewIQLMD0y3Y3DxQ4UdFiMV9AA2NzcCUE5jxAAAAAElFTkSuQmCC',
			'C56F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7WENEQxlCGUNDkMREWkUaGB0dHZDVBTSKNLA2oIk1iISwNjDCxMBOilo1denSqStDs5DcF9DA0OiKbh5IrCEQ3Q4MMZFW1lZ0t7CGMIYA3YwiNlDhR0WIxX0Au2bKKx55qwgAAAAASUVORK5CYII=',
			'F849' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7QkMZQxgaHaY6IIkFNLC2MrQ6BASgiIkAVTk6iKCrC4SLgZ0UGrUybGVmVlQYkvtA6liBukXQzHMNBZLodjQ6YNrRiO4WTDcPVPhREWJxHwDHTs5kubOwNAAAAABJRU5ErkJggg==',
			'39E1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7RAMYQ1hDHVqRxQKmsLayNjBMRVHZKtLo2sAQiiI2BSwG0wt20sqopUtTQ1ctRXHfFMZAJHVQ8xgaMcVYMMSgbkERg7o5NGAQhB8VIRb3AQBpDMuYW3N0RwAAAABJRU5ErkJggg==',
			'7993' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QkMZQxhCGUIdkEVbWVsZHR0dAlDERBpdGwIaRJDFpkDEApDdF7V0aWZm1NIsJPcxOjAGOoTA1YEhawNDowOaeSINLI2OaGIBDZhuCWjA4uYBCj8qQizuAwDl2Mzbhj8qPAAAAABJRU5ErkJggg==',
			'7222' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7QkMZQxhCGaY6IIu2srYyOjoEBKCIiTS6NgQ6iCCLTWFodGgIaBBBdl/UqqWrVmYBKYT7GB2AKltBahF6WRsYAsCiSGIiIJUgUSSxAKBKsCiKmGioa2hgaMggCD8qQizuAwBUqctsWNBGLwAAAABJRU5ErkJggg==',
			'95F9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7WANEQ1lDA6Y6IImJTBFpYG1gCAhAEgtoBYkxOoigioUgiYGdNG3q1KVLQ1dFhSG5j9WVodG1gWEqsl6GVrBYA7KYQKsISAzFDpEprK3obmENYATay4Di5oEKPypCLO4DAP8gyzNSbjihAAAAAElFTkSuQmCC',
			'9873' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDA0IdkMREprC2MjQEOgQgiQW0ijQ6NAQ0iKCIAdWBRRHumzZ1ZdiqpauWZiG5j9UVqG4KQwOyeQwg8wIYUMwTAIo5OqCKgdzC2sCI4hawmxsYUNw8UOFHRYjFfQA1acy4IlNL6wAAAABJRU5ErkJggg==',
			'AE75' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7GB1EQ1lDA0MDkMRYA0SAZKADsjqRKZhiAa1AsUZHVwck90UtnRq2aunKqCgk94HVTQGagaQ3NBTIC0AVA6ljdGB0QBdjBaoMQBEDurmBYarDIAg/KkIs7gMARqPLrG+dLlAAAAAASUVORK5CYII=',
			'134A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7GB1YQxgaHVqRxVgdRFoZWh2mOiCJiToAVU11CAhA0cvQyhDo6CCC5L6VWavCVmZmZk1Dch9IHWsjXB1MrNE1NDA0BE3MAUMd0C1oYqIhIDejig1U+FERYnEfAPDzyX8R+2+3AAAAAElFTkSuQmCC',
			'997F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDA0NDkMREprC2MjQEOiCrC2gVaXTAJtboCBMDO2na1KVLs5auDM1Cch+rK2OgwxRGFL0MrQyNDgGoYgKtLEDTUMVAbmFtQBUDuxlNbKDCj4oQi/sAnvvJqwSlAeoAAAAASUVORK5CYII=',
			'9046' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7WAMYAhgaHaY6IImJTGEMYWh1CAhAEgtoZW1lmOroIIAiJtLoEOjogOy+aVOnrczMzEzNQnIfq6tIo2ujI4p5DEC9rqGBDiJIYgIgOxodUcTAbmlEdQs2Nw9U+FERYnEfAAkCzAo5WJTHAAAAAElFTkSuQmCC',
			'A44B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7GB0YWhkaHUMdkMRYAximMrQ6OgQgiYlMYQhlmOroIIIkFtDK6MoQCFcHdlLU0qVLV2ZmhmYhuS+gVaSVtRHVvNBQ0VDX0EA088BuwSoWgCmG4uaBCj8qQizuAwAY68x0mafbHAAAAABJRU5ErkJggg==',
			'1F6C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7GB1EQx1CGaYGIImxOog0MDo6BIggiYkCxVgbHB1YUPSCxBgdkN23Mmtq2NKpK7OQ3QdW5+jowIChNxCrGLodGG4JAfLQ3DxQ4UdFiMV9AATtyBoRmnJjAAAAAElFTkSuQmCC',
			'D037' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7QgMYAhhDGUNDkMQCpjCGsDY6NIggi7WytgJJNDGRRgegugAk90UtnbYya+qqlVlI7oOqa2VA1wu0iQHTjgAGDLc4OmBxM4rYQIUfFSEW9wEA2MHOFKhxv5EAAAAASUVORK5CYII=',
			'AEE1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWUlEQVR4nGNYhQEaGAYTpIn7GB1EQ1lDHVqRxVgDRBpYGximIouJTAGLhSKLBbSCxWB6wU6KWjo1bGnoqqXI7kNTB4ahoZhi2NRhFwO7OTRgEIQfFSEW9wEADS7LncmK8qIAAAAASUVORK5CYII=',
			'EAAE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7QkMYAhimMIYGIIkFNDCGMIQyOjCgiLG2Mjo6oomJNLo2BMLEwE4KjZq2MnVVZGgWkvvQ1EHFRENdQ9HFsKnDFAsNAYuhuHmgwo+KEIv7ADFpzLXb/21UAAAAAElFTkSuQmCC',
			'AC70' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nGNYhQEaGAYTpIn7GB0YQ1lDA1qRxVgDWBsdGgKmOiCJiUwRaQCKBQQgiQW0ijQwNDo6iCC5L2rptFWrlq7MmobkPrC6KYwwdWAYGgrkBaCKgdQ5OjCg2cHa6NrAgOKWgFagmxsYUNw8UOFHRYjFfQCZPs1cX8/asgAAAABJRU5ErkJggg==',
			'C275' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7WEMYQ1hDA0MDkMREWllbGRoCHZDVBTSKNDqgizUwNDo0Oro6ILkvatWqpauWroyKQnIfUN0UIGwQQdUbAISoYo2MDiAoguqWBlagWmT3sYaIhro2MEx1GAThR0WIxX0A9rvL49sKOVgAAAAASUVORK5CYII=',
			'64C6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7WAMYWhlCHaY6IImJTGGYyugQEBCAJBbQwhDK2iDoIIAs1sDoytrA6IDsvsiopUuXrlqZmoXkvpApIq1AdajmtYqGugL1iqCIMbSC7BBBdUsruluwuXmgwo+KEIv7AFApy5JMclyOAAAAAElFTkSuQmCC',
			'8863' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7WAMYQxhCGUIdkMREprC2Mjo6OgQgiQW0ijS6Njg0iKCpYwXJIblvadTKsKVTVy3NQnIfWJ2jQwOmeQEo5mETw+YWbG4eqPCjIsTiPgBI180mlKF2WwAAAABJRU5ErkJggg==',
			'82D5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nM2QMQ6AIAxFy9Ab1PvUgb0mMshpysAN8AgsnFLcSnDUxP7t5ad9KbRpFP6UT/xQ3I7BBTGMCmZMK9ueZEpet4FRgZt5Nn41tlrbEaPx672CKkrDPpCZOcZ+g0YXxcRi/VCW4AOc/IP/vZgHvwt4rsyQVkT71wAAAABJRU5ErkJggg==',
			'6653' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDHUIdkMREprC2sjYwOgQgiQW0iDSyguSQxYA81qkgGuG+yKhpYUszs5ZmIbkvZIpoK0gVinmtIo0OYBNQxVzRxEBuYXR0RHELyM0MoQwobh6o8KMixOI+ABK8zP5mpXtaAAAAAElFTkSuQmCC',
			'9008' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7WAMYAhimMEx1QBITmcIYwhDKEBCAJBbQytrK6OjoIIIiJtLo2hAAUwd20rSp01amroqamoXkPlZXFHUQCNYbiGKeABY7sLkFm5sHKvyoCLG4DwBpbcuAPkrQugAAAABJRU5ErkJggg==',
			'A98F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7GB0YQxhCGUNDkMRYA1hbGR0dHZDViUwRaXRtCEQRC2gVaXREqAM7KWrp0qVZoStDs5DcF9DKGOiIZl5oKAMW81iwiGG6BWgeyM0oYgMVflSEWNwHAHhmyi+McCJcAAAAAElFTkSuQmCC',
			'E61C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7QkMYQximMEwNQBILaGBtZQhhCBBBERNpZAxhdGBBFWtgmMLogOy+0KhpYaumrcxCdl9Ag2grkjq4eQ44xFDtALplCqpbQG5mDHVAcfNAhR8VIRb3AQBVA8umOND7OAAAAABJRU5ErkJggg==',
			'E37F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7QkNYQ1hDA0NDkMQCGkRaGRoCHRhQxBgaHTDFWhkaHWFiYCeFRq0KW7V0ZWgWkvvA6qYwYpoXgCnm6IAuJtLK2oAqBnYzmthAhR8VIRb3AQDnBcrQq2UfOwAAAABJRU5ErkJggg==',
			'9A11' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7WAMYAhimMLQii4lMYQxhCGGYiiwW0MraChQNRRUTaXRA6AU7adrUaSuzpq1aiuw+VlcUdRDYKhqKLibQiqlOZAqmGGuASKNjqENowCAIPypCLO4DAKaBzBw2usU6AAAAAElFTkSuQmCC',
			'F6CD' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7QkMZQxhCHUMdkMQCGlhbGR0CHQJQxEQaWRsEHURQxRpYGxhhYmAnhUZNC1u6amXWNCT3BTSItiKpg5vnilUM3Q5sbsF080CFHxUhFvcBAA+WzC9fEuxrAAAAAElFTkSuQmCC',
			'8325' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7WANYQxhCGUMDkMREpoi0Mjo6OiCrC2hlaHRtCEQRE5nC0MrQEOjqgOS+pVGrwlatzIyKQnIfWB1QpQiaeQ5TsIgFMDqIoLvFgSEA2X0gN7OGBkx1GAThR0WIxX0AtYPLJMXl+hsAAAAASUVORK5CYII=',
			'95C9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nM2QMQ6AMAhFcWCvCd6nS3cG6+BpcOAGrTdw6Sk1TrQ6apS/vXzCC1AuI/CnvOKHPESIPnvDKJF0npkNYyVB6T3VbMSjScZvzXnbSpkn44cBliCQ7S7oycQyp3QwV92ghNq6IHdj6/zV/x7Mjd8OnfXLuwmcw5oAAAAASUVORK5CYII=',
			'63A3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7WANYQximMIQ6IImJTBFpZQhldAhAEgtoYWh0dHRoEEEWa2BoZQWSAUjui4xaFbZ0VdTSLCT3hUxBUQfR28rQ6BoagGoeSKwBVQzkFtaGQBS3gNwMNA/FzQMVflSEWNwHABf8zf0dZt/yAAAAAElFTkSuQmCC',
			'6FF1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWUlEQVR4nGNYhQEaGAYTpIn7WANEQ11DA1qRxUSmiDSwNjBMRRYLaAGLhaKINYDFYHrBToqMmhq2NHTVUmT3hUxBUQfR20qcmAgWvawBELcEDILwoyLE4j4AvdPL1fTqI/MAAAAASUVORK5CYII=',
			'856E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7WANEQxlCGUMDkMREpog0MDo6OiCrC2gVaWBtQBUDqgthbWCEiYGdtDRq6tKlU1eGZiG5T2QKQ6MrhnlAsYZAdDswxESmsLaiu4U1gDEE3c0DFX5UhFjcBwDLIspaNpkc2AAAAABJRU5ErkJggg==',
			'94BD' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7WAMYWllDGUMdkMREpjBMZW10dAhAEgtoZQhlbQh0EEERY3QFqRNBct+0qUuXLg1dmTUNyX2sriKtSOogsFU01BXNPIFWoFvQxIBuaUV3CzY3D1T4URFicR8A+hrLNK2H0JsAAAAASUVORK5CYII=',
			'E4A9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7QkMYWhmmMEx1QBILaGCYyhDKEBCAKhbK6OjoIIIixujK2hAIEwM7KTRq6dKlq6KiwpDcF9Ag0sraEDAVVa9oqGsoUAbVDpA6ByxiKG4BuRlkHrKbByr8qAixuA8Ad0DNiSy6KCMAAAAASUVORK5CYII=',
			'5EBE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWUlEQVR4nGNYhQEaGAYTpIn7QkNEQ1lDGUMDkMQCGkQaWBsdHRjQxRoCUcQCA1DUgZ0UNm1q2NLQlaFZyO5rxTQPLIZmXgAWMZEpmHpZAzDdPFDhR0WIxX0ARejKiEwe3r0AAAAASUVORK5CYII=',
			'BB57' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7QgNEQ1hDHUNDkMQCpoi0sgJpEWSxVpFGV3QxkLqpQBrJfaFRU8OWZmatzEJyH0gdyAQGNPMcgDLoYq4NAQEMaHYwOjo6oLuZIZQRRWygwo+KEIv7ADSnzZX5DfOIAAAAAElFTkSuQmCC',
			'C793' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7WENEQx1CGUIdkMREWhkaHR0dHQKQxAIaGRpdGwIaRJDFGhhaWYFkAJL7olatmrYyM2ppFpL7gPIBDCFwdVAxRgcGdPMaWRsY0cREWkUaGNHcwhoCVIHm5oEKPypCLO4DAJwAzTSIZEdKAAAAAElFTkSuQmCC',
			'1AD3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7GB0YAlhDGUIdkMRYHRhDWBsdHQKQxEQdWFtZGwIaRFD0ijS6AsUCkNy3MmvaytRVUUuzkNyHpg4qJhrqisM8DDF0t4QAxdDcPFDhR0WIxX0AHbnLsQoxCJUAAAAASUVORK5CYII=',
			'F595' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7QkNFQxlCGUMDkMQCGkQaGB0dHRjQxFgbAtHFQoBirg5I7guNmrp0ZWZkVBSS+4BmNzqEgExA1gsUa0AXE2l0BNqBKsbayujoEIDqPsYQhlCGqQ6DIPyoCLG4DwDF5MzqWzpyVQAAAABJRU5ErkJggg==',
			'DBB3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWUlEQVR4nGNYhQEaGAYTpIn7QgNEQ1hDGUIdkMQCpoi0sjY6OgQgi7WKNLo2BDSIoIoB1Tk0BCC5L2rp1LCloauWZiG5D00dPvMwxbC4BZubByr8qAixuA8ApgTPy7O2bucAAAAASUVORK5CYII=',
			'4FBC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpI37poiGuoYyTA1AFgsRaWBtdAgQQRJjBIk1BDqwIImxTgGpc3RAdt+0aVPDloauzEJ2XwCqOjAMDYWYh+oWTDvAYmhuAYuhu3mgwo96EIv7AG3Zy60bLWQYAAAAAElFTkSuQmCC',
			'27B7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nM2QsQ2AMAwEncIbhH1Ckf4LvARTJEU2iLIBBZkSi8oRlCDwdSdLPpn6ZRL9iVf6GJNEcbIY5yvlmEPyxqGoSxgcFSqse7B9rbdN+r7aPhB0r9i7LrjACXVoOQGs8wrnOVgnok7c4L7634Pc9B1b4sv06smauQAAAABJRU5ErkJggg==',
			'11AF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXUlEQVR4nGNYhQEaGAYTpIn7GB0YAhimMIaGIImxOjAGMIQCZZDERB1YAxgdHR3Q9bI2BMLEwE5ambUqaumqyNAsJPehqUOIhWIRw6YOTUw0hDUUXWygwo+KEIv7AC2axSKVht4bAAAAAElFTkSuQmCC',
			'207B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nM2QwQmAMAxFm0M26EBxgxSaJTpFeugG0g081CmtiNCgR0WT2+Pn84hbL6PuT/uKH7JjlCA0MD9DdBqIB8YFy878eF18pjyducOp1paWJmn0456bwfQBdcZg+lCxAFnmFSKqvRXpzgrG+av/Pbg3fhtQSMpyx/ij7gAAAABJRU5ErkJggg==',
			'4F1C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpI37poiGOkxhmBqALBYiAsQMASJIYoxAMcYQRgcWJDHWKUAVUxgdkN03bdrUsFXTVmYhuy8AVR0YhoZiijFA1bFgiKG6BSTGGOqA6uaBCj/qQSzuAwC3A8pfIM9geAAAAABJRU5ErkJggg==',
			'BA34' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7QgMYAhhDGRoCkMQCpjCGsDY6NKKItbK2gkhUdSJAVQ5TApDcFxo1bWXW1FVRUUjug6hzdEA1TzTUoSEwNARFDKgO6BJ0O1zBoshuFml0RHPzQIUfFSEW9wEA9kvRIIL0vCoAAAAASUVORK5CYII=',
			'54C7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nGNYhQEaGAYTpIn7QkMYWhlCHUNDkMQCGhimMjoENIigioWyNgigiAUGMLqyQuTg7gubtnTp0lWrVmYhu69VpBWorhXF5lbRUNcGhinIYgGtDEB1AgHIYiJTGFoZHQIdkMVYA8BuRhEbqPCjIsTiPgCpMstsccdHMAAAAABJRU5ErkJggg==',
			'2A1F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7WAMYAhimMIaGIImJTGEMYQhhdEBWF9DK2sqIJsbQKtLoMAUuBnHTtGkrs6atDM1Cdl8AijowZHQQDUUXY23AVCeCRSw0VKTRMdQR1S0DFH5UhFjcBwBAdclIphMuKQAAAABJRU5ErkJggg==',
			'E4B1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7QkMYWllDGVqRxQIaGKayNjpMRRMLZW0ICEUVY3QFqoPpBTspNGrp0qWhq5Yiuy+gQaQVSR1UTDTUtSEA3d5WVmxiaHqhbg4NGAThR0WIxX0A9IXNuOIJ2j8AAAAASUVORK5CYII=',
			'6B68' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7WANEQxhCGaY6IImJTBFpZXR0CAhAEgtoEWl0bXB0EEEWaxBpZW1ggKkDOykyamrY0qmrpmYhuS8EaB4runmtIPMCUc3DIobNLdjcPFDhR0WIxX0AIRLNAiBFp0sAAAAASUVORK5CYII=',
			'B997' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7QgMYQxhCGUNDkMQCprC2Mjo6NIggi7WKNLo2BKCKTYGIBSC5LzRq6dLMzKiVWUjuC5jCGOgQEtDKgGIeQ6MDUAZVjKXRsSEggAHDLY4OWNyMIjZQ4UdFiMV9AC3+zYaMZFIBAAAAAElFTkSuQmCC',
			'E1AF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXUlEQVR4nGNYhQEaGAYTpIn7QkMYAhimMIaGIIkFNDAGMIQyOjCgiLEGMDo6ookxBLA2BMLEwE4KjVoVtXRVZGgWkvvQ1CHEQrGIYVOHJhYawhqKLjZQ4UdFiMV9AFZHyTdvcCqIAAAAAElFTkSuQmCC',
			'3BA6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7RANEQximMEx1QBILmCLSyhDKEBCArLJVpNHR0dFBAFkMqI61IdAB2X0ro6aGLV0VmZqF7D6IOgzzXEMDHUTQxRpQxQLAegNQ9ILcDBRDcfNAhR8VIRb3AQB6A8yzK4Fm+QAAAABJRU5ErkJggg==',
			'61B9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7WAMYAlhDGaY6IImJTGEMYG10CAhAEgtoYQ1gbQh0EEEWawDqbXSEiYGdFBm1Kmpp6KqoMCT3hUwBqXOYiqK3FSgGNAGLGIodIhC9KG4BuiQU3c0DFX5UhFjcBwBiosrWdq2xcwAAAABJRU5ErkJggg==',
			'3854' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7RAMYQ1hDHRoCkMQCprC2sjYwNCKLMbSKNLoCSRQxkLqpDFMCkNy3Mmpl2NLMrKgoZPcB1TE0BDqgm+fQEBgagmFHAIZbGB1R3QdyM0MoA4rYQIUfFSEW9wEA7vrNoi4xx9QAAAAASUVORK5CYII=',
			'B5C0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7QgNEQxlCHVqRxQKmiDQwOgRMdUAWaxVpYG0QCAhAVRfCClQpguS+0KipS5euWpk1Dcl9AVMYGl0R6qDmYRMTAYqh28Haiu6W0ADGEHQ3D1T4URFicR8AAfjNrXuZSkcAAAAASUVORK5CYII=',
			'8EF9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7WANEQ1lDA6Y6IImJTBFpYG1gCAhAEgtoBYkxOohgqIOLgZ20NGpq2NLQVVFhSO6DmjdVBMM8oBymGBY7UN0CdjPQPGQ3D1T4URFicR8A4GHLKdtkK/oAAAAASUVORK5CYII=',
			'A9A4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7GB0YQximMDQEIImxBrC2MoQyNCKLiUwRaXR0dGhFFgtoFWl0bQiYEoDkvqilS5emroqKikJyX0ArY6BrQ6ADst5QoPmuoYGhISjmsYDMa0C1g7WVFUOMMQRdbKDCj4oQi/sAtR3PhdoqduEAAAAASUVORK5CYII=',
			'2826' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAd0lEQVR4nGNYhQEaGAYTpIn7WAMYQxhCGaY6IImJTGFtZXR0CAhAEgtoFWl0bQh0EEDW3craygAUQ3HftJVhq1ZmpmYhuy8AqK6VEcU8RgeRRocpQBLZLQ1AsQBUMZEGoFscGFD0hoYyhrCGBqC4eaDCj4oQi/sATIPKqC9YgMYAAAAASUVORK5CYII=',
			'0128' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7GB0YAhhCGaY6IImxBjAGMDo6BAQgiYlMYQ1gbQh0EEESC2gF6m0IgKkDOylq6aqoVSuzpmYhuQ+sDogD0PVOYUQxT2QKUCwAVYwVLIKql9GBNZQ1NADFzQMVflSEWNwHABv+yN6/CwNtAAAAAElFTkSuQmCC',
			'A119' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7GB0YAhimMEx1QBJjDWAMYAhhCAhAEhOZAhQNYXQQQRILaAXphYuBnRS1dFXUqmmrosKQ3AdRxzAVWW9oKFisAdM8Bix2oLoloJU1lDHUAcXNAxV+VIRY3AcAjoPJtaAYAl0AAAAASUVORK5CYII=',
			'2229' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7WAMYQxhCGaY6IImJTGFtZXR0CAhAEgtoFWl0bQh0EEHW3crQ6IAQg7hp2qqlq1ZmRYUhuy+AYQpQ7VRkvYwOYNEGZDFWiCiKHSJQUWS3hIaKhrqGBqC4eaDCj4oQi/sAaUHKsIeYbxkAAAAASUVORK5CYII=',
			'B6F2' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QgMYQ1hDA6Y6IIkFTGFtZW1gCAhAFmsVaWRtYHQQQVEn0gBU1yCC5L7QqGlhS0NXrYpCcl/AFFGQeY0OaOa5NjC0MmCKTWHA4hYMNzcwhoYMgvCjIsTiPgBdoc0WPRTZHgAAAABJRU5ErkJggg==',
			'8BA9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7WANEQximMEx1QBITmSLSyhDKEBCAJBbQKtLo6OjoIIKmjrUhECYGdtLSqKlhS1dFRYUhuQ+iLmCqCJp5rqEBDRhiDQFY7AhAcQvIzSDzkN08UOFHRYjFfQBY9M1v9gL0BQAAAABJRU5ErkJggg==',
			'EAB8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7QkMYAlhDGaY6IIkFNDCGsDY6BASgiLG2sjYEOoigiIk0uiLUgZ0UGjVtZWroqqlZSO5DUwcVEw11xWYefjugbgaKobl5oMKPihCL+wBrHM8Uli9rUwAAAABJRU5ErkJggg==',
			'321E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7RAMYQximMIYGIIkFTGFtZQhhdEBR2SrS6IguNoWh0WEKXAzspJVRq5aumrYyNAvZfVNAEN08hgBMMSAfTQzolgZ0MdEA0VBHIER280CFHxUhFvcBAFYFySHBpUsdAAAAAElFTkSuQmCC',
			'518D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7QkMYAhhCGUMdkMQCGhgDGB0dHQJQxFgDWBsCHUSQxAIDGMDqRJDcFzZtVdSq0JVZ05Dd14qiDi6Gbl4AFjGRKQwYbgG6JBTdzQMVflSEWNwHAPEByLHrfkuOAAAAAElFTkSuQmCC',
			'10D4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7GB0YAlhDGRoCkMRYHRhDWBsdGpHFRB1YW1kbAloDUPSKNLo2BEwJQHLfyqxpK1NXRUVFIbkPoi7QAVNvYGgIihjYjgZUdWC3oIiJhmC6eaDCj4oQi/sASNnLjss8CiAAAAAASUVORK5CYII=',
			'74B5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nM2QsRGAMAhFScEGcR8s0mNBk2lIkQ3MCCl0SpOOqKXehd9w7+B4B5yPUpgpv/iJQEZxwpZmKJhWgpEJ6jay3YU2F8j6xVqrHDEaP0c+YyL1Zhd1kaA8sNbnfsMy7iwR850JFJrgfx/mxe8CFlfLpJ+kEJkAAAAASUVORK5CYII=',
			'61A6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7WAMYAhimMEx1QBITmcIYwBDKEBCAJBbQwhrA6OjoIIAs1sAQwNoQ6IDsvsioVVFLV0WmZiG5L2QKWB2qea1AsdBABxF0sQZUMRGw3gAUvaxAnUAxFDcPVPhREWJxHwDLG8qOdiZcLgAAAABJRU5ErkJggg==',
			'EDF6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAV0lEQVR4nGNYhQEaGAYTpIn7QkNEQ1hDA6Y6IIkFNIi0sjYwBASgijW6NjA6CGARQ3ZfaNS0lamhK1OzkNwHVYfVPBHCYhhuAbu5gQHFzQMVflSEWNwHAHZVzTMK4AiVAAAAAElFTkSuQmCC',
			'C8C9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7WEMYQxhCHaY6IImJtLK2MjoEBAQgiQU0ijS6Ngg6iCCLNbC2sjYwwsTATopatTJsKZAKQ3IfRB3DVFS9IPOAdmHYIYBiBza3YHPzQIUfFSEW9wEAYIfMaNNRTKMAAAAASUVORK5CYII=',
			'362A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7RAMYQxhCGVqRxQKmsLYyOjpMdUBW2SrSyNoQEBCALDZFBEgGOogguW9l1LSwVSszs6Yhu2+KaCtDKyNMHdw8hymMoSHoYgGo6sBucUAVA7mZNTQQ1bwBCj8qQizuAwBW5MqHDw7mVwAAAABJRU5ErkJggg==',
			'BA7B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7QgMYAlhDA0MdkMQCpjCGMDQEOgQgi7WytoLERFDUiTQ6NDrC1IGdFBo1bWXW0pWhWUjuA6ubwohmnmioQwAjqnmtIkDTGDHscG1A1RsaABZDcfNAhR8VIRb3AQAHaM20fptqFQAAAABJRU5ErkJggg==',
			'6796' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7WANEQx1CGaY6IImJTGFodHR0CAhAEgtoYWh0bQh0EEAWa2BoZQWKIbsvMmrVtJWZkalZSO4LmcIQwBASiGpeKyNQX6CDCIoYawMjmpjIFJEGRjS3sAYAVaC5eaDCj4oQi/sACG3L+wx5ue0AAAAASUVORK5CYII=',
			'3130' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7RAMYAhhDGVqRxQKmMAawNjpMdUBW2coaAJQJCEAWm8IQwNDo6CCC5L6VUauiVk1dmTUN2X2o6qDmAcUaArGIodoRANSL7hbRANZQdDcPVPhREWJxHwANZsqApBsP1QAAAABJRU5ErkJggg==',
			'F4AC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7QkMZWhmmMEwNQBIDsqcyhDIEiKCKhTI6OjqwoIgxurI2BDoguy80aunSpasis5DdF9Ag0oqkDiomGuoaii7GAFbHgiEWgO4WkBiKmwcq/KgIsbgPAJKxzM78nfmcAAAAAElFTkSuQmCC',
			'D685' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QgMYQxhCGUMDkMQCprC2Mjo6OiCrC2gVaWRtCEQXawCqc3VAcl/U0mlhq0JXRkUhuS+gVRRonkODCJp5rg0BWMQCHUQw3OIQgOw+iJsZpjoMgvCjIsTiPgCeEczJJjgLygAAAABJRU5ErkJggg==',
			'7F01' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7QkNFQx2mMLSiiLaKNDCEMkxFF2N0dAhFEZsi0sDaEADTC3FT1NSwpauiliK7j9EBRR0YsjZgiok0gO1AEQtoALsFU2wKQ2jAIAg/KkIs7gMAd2DLwF92vmIAAAAASUVORK5CYII=',
			'4891' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpI37pjCGMIQytKKIhbC2Mjo6TEUWYwwRaXRtCAhFFmOdwtrK2hAA0wt20rRpK8NWZkYtRXZfAFAdQ0gAih2hoSKNDg2oYgxTRBodMcTAbkETA7s5NGAwhB/1IBb3AQCcl8vs4WArgwAAAABJRU5ErkJggg==',
			'D73B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7QgNEQx1DGUMdkMQCpjA0ujY6OgQgi7UyNDo0BDqIoIoBReHqwE6KWrpq2qqpK0OzkNwHVBfAgGEeowMDhnmsDRhiU0QaWNH0hgaINDCiuXmgwo+KEIv7AJPZzfQohMLgAAAAAElFTkSuQmCC',
			'5C48' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7QkMYQxkaHaY6IIkFNLA2OrQ6BASgiIk0OEx1dBBBEgsMAPIC4erATgqbNm3VysysqVnI7msVAZmIYh5YLDQQxbwAoJhDI6odIlOAOtH0sgZgunmgwo+KEIv7AMNTzhz/LsR4AAAAAElFTkSuQmCC',
			'3FF1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAVUlEQVR4nGNYhQEaGAYTpIn7RANEQ11DA1qRxQKmiDSwNjBMRVHZChYLRRGDqIPpBTtpZdTUsKWhq5aiuA9VHbJ5BMUCsOgVDYC4JWAQhB8VIRb3AQAHlMs+8btqdAAAAABJRU5ErkJggg==',
			'0652' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7GB0YQ1hDHaY6IImxBrC2sjYwBAQgiYlMEWlkBaoWQRILaBVpYJ0KlENyX9TSaWFLM7NWRSG5L6BVtBVINjqg6gXyA1oZ0OxwbQiYwoDmFkZHhwB0NzOEMoaGDILwoyLE4j4AZRXLlIAqYdwAAAAASUVORK5CYII=',
			'7397' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7QkNZQxhCGUNDkEVbRVoZHR0aRFDEGBpdGwJQxaYwtLICxQKQ3Re1KmxlZtTKLCT3MToAdYcEtCLby9rA0OjQEDAFWQxodqNjQ0AAshjQRqBbHB1QxcBuRhEbqPCjIsTiPgBdvMtriyFfQAAAAABJRU5ErkJggg==',
			'E46E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7QkMYWhlCGUMDkMSA7KmMjo4ODKhioawN6GKMrqwNjDAxsJNCo5YuXTp1ZWgWkvsCGkRaWTHMEw11bQhEt6OVFYsYuluwuXmgwo+KEIv7ADHyyqpUWNO1AAAAAElFTkSuQmCC',
			'93C2' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7WANYQxhCHaY6IImJTBFpZXQICAhAEgtoZWh0bRB0EEEVa2UFqUdy37Spq8KWAukoJPexuoLVNSLbwQA2D0giiQmAxQSmMGBxC6abHUNDBkH4URFicR8A+LHL47t08d0AAAAASUVORK5CYII=',
			'25E3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7WANEQ1lDHUIdkMREpog0sDYwOgQgiQW0gsSAcsi6W0VCQGIByO6bNnXp0tBVS7OQ3RfA0OiKUAeGjA4QMWTzWBtEMMSAtraiuyU0lDEE3c0DFX5UhFjcBwCu/8vS+t+8IwAAAABJRU5ErkJggg==',
			'76C6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7QkMZQxhCHaY6IIu2srYyOgQEBKCIiTSyNgg6CCCLTRFpYG1gdEBxX9S0sKWrVqZmIbmP0UG0FagOxTzWBpFGV6CMCJKYCFhMEEUsoAHTLQENWNw8QOFHRYjFfQDBrcs74DUwDgAAAABJRU5ErkJggg==',
			'DC4A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7QgMYQxkaHVqRxQKmsAJFHKY6IIu1ijQARQIC0MQYAh0dRJDcF7V02qqVmZlZ05DcB1LH2ghXhxALDQwNQbcDXR3ILWhiEDejig1U+FERYnEfAKjTzsgJn9OZAAAAAElFTkSuQmCC',
			'9ABA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7WAMYAlhDGVqRxUSmMIawNjpMdUASC2hlbWVtCAgIQBETaXRtdHQQQXLftKnTVqaGrsyahuQ+VlcUdRDYKhrq2hAYGoIkJgAyryEQRZ3IFEy9rAFAsVBGVPMGKPyoCLG4DwDKCcy6yfJDDQAAAABJRU5ErkJggg==',
			'2B8F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7WANEQxhCGUNDkMREpoi0Mjo6OiCrC2gVaXRtCEQRY2hFUQdx07SpYatCV4ZmIbsvANM8RgdM81gbMMVEGjD1hoaC3YzqlgEKPypCLO4DAHXKySA2dKvsAAAAAElFTkSuQmCC',
			'4AD1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpI37pjAEsIYytKKIhTCGsDY6TEUWA4q0sjYEhCKLsU4RaXRtCIDpBTtp2rRpK1NXRS1Fdl8AqjowDA0VDUUXY8CiDizW6IApFsoQGjAYwo96EIv7AG4rzaaebbQzAAAAAElFTkSuQmCC',
			'1227' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7GB0YQxhCGUNDkMRYHVhbGR0dGkSQxEQdRBpdGwJQxBgdGBodgGIBSO5bmbVqKZAAUgj3AdVNYWgFQlS9AUDRKWhuAYkGoIqxQsSR3RIiGuoaGogiNlDhR0WIxX0AkL/ILPhG/kMAAAAASUVORK5CYII=',
			'8865' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7WAMYQxhCGUMDkMREprC2Mjo6OiCrC2gVaXRtQBUDqWNtYHR1QHLf0qiVYUunroyKQnIfWJ2jQ4MIhnkBWMQCHUQw3OIQgOw+iJsZpjoMgvCjIsTiPgBYQ8vCjNqrpQAAAABJRU5ErkJggg==',
			'9E58' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7WANEQ1lDHaY6IImJTBFpYG1gCAhAEgtoBYkxOoigi02FqwM7adrUqWFLM7OmZiG5j9UVpCsAxTyGVpBYIIp5AmA7UMVAbmF0dEDRC3IzQygDipsHKvyoCLG4DwBdKMtzG8tj8gAAAABJRU5ErkJggg==',
			'544C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7QkMYWhkaHaYGIIkB2VMZWh0CRFDFQhmmOjqwIIkFBjC6MgQ6OiC7L2za0qUrMzOzUNzXKtLK2ghXBxUTDXUNDUQRC2gFuQXVDpEpYPehuIU1ANPNAxV+VIRY3AcA4SHL24k2gqMAAAAASUVORK5CYII='        
        );
        $this->text = array_rand( $images );
        return $images[ $this->text ] ;    
    }
    
    function out_processing_gif(){
        $image = dirname(__FILE__) . '/processing.gif';
        $base64_image = "R0lGODlhFAAUALMIAPh2AP+TMsZiALlcAKNOAOp4ANVqAP+PFv///wAAAAAAAAAAAAAAAAAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh+QQFCgAIACwAAAAAFAAUAAAEUxDJSau9iBDMtebTMEjehgTBJYqkiaLWOlZvGs8WDO6UIPCHw8TnAwWDEuKPcxQml0Ynj2cwYACAS7VqwWItWyuiUJB4s2AxmWxGg9bl6YQtl0cAACH5BAUKAAgALAEAAQASABIAAAROEMkpx6A4W5upENUmEQT2feFIltMJYivbvhnZ3Z1h4FMQIDodz+cL7nDEn5CH8DGZhcLtcMBEoxkqlXKVIgAAibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkphaA4W5upMdUmDQP2feFIltMJYivbvhnZ3V1R4BNBIDodz+cL7nDEn5CH8DGZAMAtEMBEoxkqlXKVIg4HibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpjaE4W5tpKdUmCQL2feFIltMJYivbvhnZ3R0A4NMwIDodz+cL7nDEn5CH8DGZh8ONQMBEoxkqlXKVIgIBibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpS6E4W5spANUmGQb2feFIltMJYivbvhnZ3d1x4JMgIDodz+cL7nDEn5CH8DGZgcBtMMBEoxkqlXKVIggEibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpAaA4W5vpOdUmFQX2feFIltMJYivbvhnZ3V0Q4JNhIDodz+cL7nDEn5CH8DGZBMJNIMBEoxkqlXKVIgYDibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpz6E4W5tpCNUmAQD2feFIltMJYivbvhnZ3R1B4FNRIDodz+cL7nDEn5CH8DGZg8HNYMBEoxkqlXKVIgQCibbK9YLBYvLtHH5K0J0IACH5BAkKAAgALAEAAQASABIAAAROEMkpQ6A4W5spIdUmHQf2feFIltMJYivbvhnZ3d0w4BMAIDodz+cL7nDEn5CH8DGZAsGtUMBEoxkqlXKVIgwGibbK9YLBYvLtHH5K0J0IADs=";
        $binary = is_file($image) ? join("",file($image)) : base64_decode($base64_image); 
        header("Cache-Control: post-check=0, pre-check=0, max-age=0, no-store, no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: image/gif");
        echo $binary;
    }

}
# end of class phpfmgImage
# ------------------------------------------------------
# end of module : captcha


# module user
# ------------------------------------------------------
function phpfmg_user_isLogin(){
    return ( isset($_SESSION['authenticated']) && true === $_SESSION['authenticated'] );
}


function phpfmg_user_logout(){
    session_destroy();
    header("Location: admin.php");
}

function phpfmg_user_login()
{
    if( phpfmg_user_isLogin() ){
        return true ;
    };
    
    $sErr = "" ;
    if( 'Y' == $_POST['formmail_submit'] ){
        if(
            defined( 'PHPFMG_USER' ) && strtolower(PHPFMG_USER) == strtolower($_POST['Username']) &&
            defined( 'PHPFMG_PW' )   && strtolower(PHPFMG_PW) == strtolower($_POST['Password']) 
        ){
             $_SESSION['authenticated'] = true ;
             return true ;
             
        }else{
            $sErr = 'Login failed. Please try again.';
        }
    };
    
    // show login form 
    phpfmg_admin_header();
?>
<form name="frmFormMail" action="" method='post' enctype='multipart/form-data'>
<input type='hidden' name='formmail_submit' value='Y'>
<br><br><br>

<center>
<div style="width:380px;height:260px;">
<fieldset style="padding:18px;" >
<table cellspacing='3' cellpadding='3' border='0' >
	<tr>
		<td class="form_field" valign='top' align='right'>Email :</td>
		<td class="form_text">
            <input type="text" name="Username"  value="<?php echo $_POST['Username']; ?>" class='text_box' >
		</td>
	</tr>

	<tr>
		<td class="form_field" valign='top' align='right'>Password :</td>
		<td class="form_text">
            <input type="password" name="Password"  value="" class='text_box'>
		</td>
	</tr>

	<tr><td colspan=3 align='center'>
        <input type='submit' value='Login'><br><br>
        <?php if( $sErr ) echo "<span style='color:red;font-weight:bold;'>{$sErr}</span><br><br>\n"; ?>
        <a href="admin.php?mod=mail&func=request_password">I forgot my password</a>   
    </td></tr>
</table>
</fieldset>
</div>
<script type="text/javascript">
    document.frmFormMail.Username.focus();
</script>
</form>
<?php
    phpfmg_admin_footer();
}


function phpfmg_mail_request_password(){
    $sErr = '';
    if( $_POST['formmail_submit'] == 'Y' ){
        if( strtoupper(trim($_POST['Username'])) == strtoupper(trim(PHPFMG_USER)) ){
            phpfmg_mail_password();
            exit;
        }else{
            $sErr = "Failed to verify your email.";
        };
    };
    
    $n1 = strpos(PHPFMG_USER,'@');
    $n2 = strrpos(PHPFMG_USER,'.');
    $email = substr(PHPFMG_USER,0,1) . str_repeat('*',$n1-1) . 
            '@' . substr(PHPFMG_USER,$n1+1,1) . str_repeat('*',$n2-$n1-2) . 
            '.' . substr(PHPFMG_USER,$n2+1,1) . str_repeat('*',strlen(PHPFMG_USER)-$n2-2) ;


    phpfmg_admin_header("Request Password of Email Form Admin Panel");
?>
<form name="frmRequestPassword" action="admin.php?mod=mail&func=request_password" method='post' enctype='multipart/form-data'>
<input type='hidden' name='formmail_submit' value='Y'>
<br><br><br>

<center>
<div style="width:580px;height:260px;text-align:left;">
<fieldset style="padding:18px;" >
<legend>Request Password</legend>
Enter Email Address <b><?php echo strtoupper($email) ;?></b>:<br />
<input type="text" name="Username"  value="<?php echo $_POST['Username']; ?>" style="width:380px;">
<input type='submit' value='Verify'><br>
The password will be sent to this email address. 
<?php if( $sErr ) echo "<br /><br /><span style='color:red;font-weight:bold;'>{$sErr}</span><br><br>\n"; ?>
</fieldset>
</div>
<script type="text/javascript">
    document.frmRequestPassword.Username.focus();
</script>
</form>
<?php
    phpfmg_admin_footer();    
}


function phpfmg_mail_password(){
    phpfmg_admin_header();
    if( defined( 'PHPFMG_USER' ) && defined( 'PHPFMG_PW' ) ){
        $body = "Here is the password for your form admin panel:\n\nUsername: " . PHPFMG_USER . "\nPassword: " . PHPFMG_PW . "\n\n" ;
        if( 'html' == PHPFMG_MAIL_TYPE )
            $body = nl2br($body);
        mailAttachments( PHPFMG_USER, "Password for Your Form Admin Panel", $body, PHPFMG_USER, 'You', "You <" . PHPFMG_USER . ">" );
        echo "<center>Your password has been sent.<br><br><a href='admin.php'>Click here to login again</a></center>";
    };   
    phpfmg_admin_footer();
}


function phpfmg_writable_check(){
 
    if( is_writable( dirname(PHPFMG_SAVE_FILE) ) && is_writable( dirname(PHPFMG_EMAILS_LOGFILE) )  ){
        return ;
    };
?>
<style type="text/css">
    .fmg_warning{
        background-color: #F4F6E5;
        border: 1px dashed #ff0000;
        padding: 16px;
        color : black;
        margin: 10px;
        line-height: 180%;
        width:80%;
    }
    
    .fmg_warning_title{
        font-weight: bold;
    }

</style>
<br><br>
<div class="fmg_warning">
    <div class="fmg_warning_title">Your form data or email traffic log is NOT saving.</div>
    The form data (<?php echo PHPFMG_SAVE_FILE ?>) and email traffic log (<?php echo PHPFMG_EMAILS_LOGFILE?>) will be created automatically when the form is submitted. 
    However, the script doesn't have writable permission to create those files. In order to save your valuable information, please set the directory to writable.
     If you don't know how to do it, please ask for help from your web Administrator or Technical Support of your hosting company.   
</div>
<br><br>
<?php
}


function phpfmg_log_view(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );
    
    phpfmg_admin_header();
   
    $file = $files[$n];
    if( is_file($file) ){
        if( 1== $n ){
            echo "<pre>\n";
            echo join("",file($file) );
            echo "</pre>\n";
        }else{
            $man = new phpfmgDataManager();
            $man->displayRecords();
        };
     

    }else{
        echo "<b>No form data found.</b>";
    };
    phpfmg_admin_footer();
}


function phpfmg_log_download(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );

    $file = $files[$n];
    if( is_file($file) ){
        phpfmg_util_download( $file, PHPFMG_SAVE_FILE == $file ? 'form-data.csv' : 'email-traffics.txt', true, 1 ); // skip the first line
    }else{
        phpfmg_admin_header();
        echo "<b>No email traffic log found.</b>";
        phpfmg_admin_footer();
    };

}


function phpfmg_log_delete(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );
    phpfmg_admin_header();

    $file = $files[$n];
    if( is_file($file) ){
        echo unlink($file) ? "It has been deleted!" : "Failed to delete!" ;
    };
    phpfmg_admin_footer();
}


function phpfmg_util_download($file, $filename='', $toCSV = false, $skipN = 0 ){
    if (!is_file($file)) return false ;

    set_time_limit(0);


    $buffer = "";
    $i = 0 ;
    $fp = @fopen($file, 'rb');
    while( !feof($fp)) { 
        $i ++ ;
        $line = fgets($fp);
        if($i > $skipN){ // skip lines
            if( $toCSV ){ 
              $line = str_replace( chr(0x09), ',', $line );
              $buffer .= phpfmg_data2record( $line, false );
            }else{
                $buffer .= $line;
            };
        }; 
    }; 
    fclose ($fp);
  

    
    /*
        If the Content-Length is NOT THE SAME SIZE as the real conent output, Windows+IIS might be hung!!
    */
    $len = strlen($buffer);
    $filename = basename( '' == $filename ? $file : $filename );
    $file_extension = strtolower(substr(strrchr($filename,"."),1));

    switch( $file_extension ) {
        case "pdf": $ctype="application/pdf"; break;
        case "exe": $ctype="application/octet-stream"; break;
        case "zip": $ctype="application/zip"; break;
        case "doc": $ctype="application/msword"; break;
        case "xls": $ctype="application/vnd.ms-excel"; break;
        case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
        case "gif": $ctype="image/gif"; break;
        case "png": $ctype="image/png"; break;
        case "jpeg":
        case "jpg": $ctype="image/jpg"; break;
        case "mp3": $ctype="audio/mpeg"; break;
        case "wav": $ctype="audio/x-wav"; break;
        case "mpeg":
        case "mpg":
        case "mpe": $ctype="video/mpeg"; break;
        case "mov": $ctype="video/quicktime"; break;
        case "avi": $ctype="video/x-msvideo"; break;
        //The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
        case "php":
        case "htm":
        case "html": 
                $ctype="text/plain"; break;
        default: 
            $ctype="application/x-download";
    }
                                            

    //Begin writing headers
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public"); 
    header("Content-Description: File Transfer");
    //Use the switch-generated Content-Type
    header("Content-Type: $ctype");
    //Force the download
    header("Content-Disposition: attachment; filename=".$filename.";" );
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: ".$len);
    
    while (@ob_end_clean()); // no output buffering !
    flush();
    echo $buffer ;
    
    return true;
 
    
}
?>