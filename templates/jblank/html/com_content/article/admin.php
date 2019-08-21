<?php
require_once( dirname(__FILE__).'/form.lib.php' );

define( 'PHPFMG_USER', "nikolayblinov@yandex.ru" ); // must be a email address. for sending password to you.
define( 'PHPFMG_PW', "3c2294" );

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
			'78A1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7QkMZQximMLSiiLaytjKEMkxFFRNpdHR0CEURm8LaytoQANMLcVPUyrClq6KWIruP0QFFHRiyNog0uoaiiomAxNDUBTRg6g1oYAwBioUGDILwoyLE4j4AZdDMxGN3MYwAAAAASUVORK5CYII=',
			'3EBA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7RANEQ1lDGVqRxQKmiDSwNjpMdUBW2QoUawgICEAWA6tzdBBBct/KqKlhS0NXZk1Ddh+qOiTzAkNDMMVQ1AVg0QtxMyOqeQMUflSEWNwHAKrJy53GvmkNAAAAAElFTkSuQmCC',
			'7E56' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7QkNFQ1lDHaY6IIu2ijSwNjAEBGCIMToIIItNAYpNZXRAcV/U1LClmZmpWUjuY3QQAZKBKOaxNoDFwDIwKNIAsgNVLAAoxujogKI3oEE0lCGUAdXNAxR+VIRY3AcAd6jK8/VNCJkAAAAASUVORK5CYII=',
			'AA2A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7GB0YAhhCGVqRxVgDGEMYHR2mOiCJiUxhbWVtCAgIQBILaBVpdGgIdBBBcl/U0mkrs1ZmZk1Dch9YXSsjTB0YhoaKhjpMYQwNQTcvAFUdSMzRAVPMNTQQRWygwo+KEIv7AMvRzDUehUjrAAAAAElFTkSuQmCC',
			'A1F5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7GB0YAlhDA0MDkMRYAxgDWEEySGIiU1gxxAJaGUBirg5I7otaCkShK6OikNwHUQc0A0lvaCimGNQ8B0wxhoAAFDHWUKDYVIdBEH5UhFjcBwC5k8kIhS8oEQAAAABJRU5ErkJggg==',
			'BBC8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXElEQVR4nGNYhQEaGAYTpIn7QgNEQxhCHaY6IIkFTBFpZXQICAhAFmsVaXRtEHQQQVPH2sAAUwd2UmjU1LClq1ZNzUJyH5o6JPMYUc3DYQe6W7C5eaDCj4oQi/sAkrTOGUwUZV8AAAAASUVORK5CYII=',
			'E2C5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7QkMYQxhCHUMDkMQCGlhbGR0CHRhQxEQaXRsE0cQYgGKMrg5I7guNWrV06aqVUVFI7gOqm8IKpEVQ9QZgijE6sALtQBVjBYoGBCC7LzRENNQh1GGqwyAIPypCLO4DAE9BzGXkxKGKAAAAAElFTkSuQmCC',
			'5B54' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAd0lEQVR4nGNYhQEaGAYTpIn7QkNEQ1hDHRoCkMQCGkRaWRsYGtHEGl0bGFqRxQIDgOqmMkwJQHJf2LSpYUszs6KikN3XKtIKVO2ArBco1ujQEBgagmxHK8iOABS3iEwRaWV0RHUfa4BoCEMoA4rYQIUfFSEW9wEA/gXOWPiQpB4AAAAASUVORK5CYII=',
			'E200' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QkMYQximMLQiiwU0sLYyhDJMdUARE2l0dHQICEARY2h0bQh0EEFyX2jUqqVLV0VmTUNyH1DdFFaEOphYAKYYowMjhh2sDehuCQ0RDXVAc/NAhR8VIRb3AQBDRc0EKdvqcAAAAABJRU5ErkJggg==',
			'B3A1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7QgNYQximMLQiiwVMEWllCGWYiiLWytDo6OgQiqqOoZUVJIPkvtCoVWFLV0UtRXYfmjq4ea6hWMTQ1QHdgq4X5GagWGjAIAg/KkIs7gMAlUbOZwqcAdYAAAAASUVORK5CYII=',
			'21AB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7WAMYAhimMIY6IImJTGEMYAhldAhAEgtoZQ1gdHR0EEHW3coQwNoQCFMHcdO0VVFLV0WGZiG7LwBFHRgyOgDFQgNRzGNtgKhDFhNpwNQbGsoaChRDcfNAhR8VIRb3AQAl/8kuJc18UwAAAABJRU5ErkJggg==',
			'63E0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7WANYQ1hDHVqRxUSmiLSyNjBMdUASC2hhaHRtYAgIQBZrYACqY3QQQXJfZNSqsKWhK7OmIbkvZAqKOojeVpB52MRQ7cDmFmxuHqjwoyLE4j4ApTbLzIRz0s8AAAAASUVORK5CYII=',
			'9405' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7WAMYWhmmMIYGIImJTGGYyhDK6ICsLqAVKOLoiCbG6MraEOjqgOS+aVOXLl26KjIqCsl9rK4irawNAQ0iyDa3ioa6ookJtDK0guwQQXUL0GaGAGT3QdzMMNVhEIQfFSEW9wEADezKoOO+zuQAAAAASUVORK5CYII=',
			'6522' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAeElEQVR4nM2QzQmAMAxGvx66QdwnbhChPegIThHBblBH8GCn9OeUokcF85FAHgQeQbmV4k/5xM9LExGxsGGUSV3LIobJTOq1Y7JMKVzT+PXDspZtLIPxCxkTp6Pt7blnJFSMJhZkVC4+OYbUzi742MXwg/+9mAe/HX2CzFs1xfwiAAAAAElFTkSuQmCC',
			'4E26' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpI37poiGMoQyTHVAFgsRaWB0dAgIQBJjBIqxNgQ6CCCJsU4RAZKBDsjumzZtatiqlZmpWUjuCwCpa2VEMS80FCg2hdFBBMUtQF4AphijAwOKXpCbWUMDUN08UOFHPYjFfQADBMqECA/fFAAAAABJRU5ErkJggg==',
			'A18D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7GB0YAhhCGUMdkMRYAxgDGB0dHQKQxESmsAawNgQ6iCCJBbQygNWJILkvaumqqFWhK7OmIbkPTR0YhoYyYDUPlx0BKGKsoehuHqjwoyLE4j4A3//JD1mpmkIAAAAASUVORK5CYII=',
			'06AC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7GB0YQximMEwNQBJjDWBtZQhlCBBBEhOZItLI6OjowIIkFtAq0sDaEOiA7L6opdPClq6KzEJ2X0CraCuSOpjeRtdQVDGQHa5Adch2gNzC2hCA4haQm4FiKG4eqPCjIsTiPgCVdss1juDoUQAAAABJRU5ErkJggg==',
			'64E7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7WAMYWllDHUNDkMREpjBMZQXRSGIBLQyhGGINjK6sYBrhvsiopUuXhq5amYXkvpApIq1Ada3I9ga0ioa6NjBMQRVjAKkLQBYDugUoxuiAxc0oYgMVflSEWNwHAA+8yxpso5m+AAAAAElFTkSuQmCC',
			'0F87' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7GB1EQx1CGUNDkMRYA0QaGB0dGkSQxESmiDSwNgSgiAW0QtQFILkvaunUsFWhq1ZmIbkPqq6VAU0v0LwpDJh2BDBguMXRAdXNQFeEMqKIDVT4URFicR8A69DK9HYqLFEAAAAASUVORK5CYII=',
			'1ABA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7GB0YAlhDGVqRxVgdGENYGx2mOiCJiTqwtrI2BAQEoOgVaXRtdHQQQXLfyqxpK1NDgSSS+9DUQcVEQ10bAkND0M1rCERTh6lXNAQoFsqIIjZQ4UdFiMV9AGUyyiNYvitqAAAAAElFTkSuQmCC',
			'9C28' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7WAMYQxlCGaY6IImJTGFtdHR0CAhAEgtoFWlwbQh0EEETA5IwdWAnTZs6bdWqlVlTs5Dcx+oKVNfKgGIeA0jvFEYU8wSAYg4BqGJgtzig6gW5mTU0AMXNAxV+VIRY3AcAO2fMD0b7Z/gAAAAASUVORK5CYII=',
			'CD48' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7WENEQxgaHaY6IImJtIq0MrQ6BAQgiQU0igBVOTqIIIs1AMUC4erATopaNW1lZmbW1Cwk94HUuTaimQcSCw1ENQ9kRyOqHWC3oOnF5uaBCj8qQizuAwBRD860KN7dNAAAAABJRU5ErkJggg==',
			'B482' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7QgMYWhlCGaY6IIkFTGGYyujoEBCALAZUxdoQ6CCCoo7RFaiuQQTJfaFRS5euCl21KgrJfQFTRFqB6hpR7GgVDXUFmYpqRysryHZUt4D0BmC6mTE0ZBCEHxUhFvcBAHF6zTSQeveEAAAAAElFTkSuQmCC',
			'C5AA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7WENEQxmmMLQii4m0ijQwhDJMdUASC2gUaWB0dAgIQBZrEAlhbQh0EEFyX9SqqUuXrorMmobkPqCeRleEOoRYaGBoCKodGOpEWllbWdHEWEMYQ9DFBir8qAixuA8A8BPM4h06xTMAAAAASUVORK5CYII=',
			'616E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7WAMYAhhCGUMDkMREpjAGMDo6OiCrC2hhDWBtQBNrYACKMcLEwE6KjFoVtXTqytAsJPeFTAGqQzevFaQ3kKCYCFAvuluALglFd/NAhR8VIRb3AQDPt8fvYynoRgAAAABJRU5ErkJggg==',
			'255C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpIn7WANEQ1lDHaYGIImJTBFpYG1gCBBBEgtoBYkxOrAg624VCWGdyuiA4r5pU5cuzczMQnFfAEOjQ0OgA7K9QF0YYqwNIo2uQDFkO4C2tjI6OqC4JTSUMYQhlAHFzQMVflSEWNwHACOTypQ9MM5EAAAAAElFTkSuQmCC',
			'57F0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QkNEQ11DA1qRxQIaGBpdGximOmCKBQQgiQUGMLSyNjA6iCC5L2zaqmlLQ1dmTUN2XytDAJI6qBijA7pYANA0VjQ7RKaIgMRQ3MIaABZDcfNAhR8VIRb3AQDQycuUR8f9AwAAAABJRU5ErkJggg==',
			'847F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7WAMYWllDA0NDkMREpjBMZWgIdEBWF9DKEIouJjKF0ZWh0REmBnbS0qilS1ctXRmaheQ+kSkirQxTGNHMEw11CEAXY2hldGBEswPovgZUMbCb0cQGKvyoCLG4DwA/pMmHtgS7tgAAAABJRU5ErkJggg==',
			'415C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpI37pjAEsIY6TA1AFgthDGBtYAgQQRJjDGEFijE6sCCJsYL0TmV0QHbftGmropZmZmYhuy8AqI6hIdAB2d7QUEwxsFuAYixoYoyODihuYZjCGsoQyoDq5oEKP+pBLO4DAFt5yG+pG88BAAAAAElFTkSuQmCC',
			'F043' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7QkMZAhgaHUIdkMQCGhhDGFodHQJQxFhbGaY6NIigiIk0OgQ6NAQguS80atrKzMyspVlI7gOpc22Eq0OIhQagmQe0oxHdDqBbGtHdgunmgQo/KkIs7gMAY57O0vacwXgAAAAASUVORK5CYII=',
			'2173' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nM2QsRGAMAhFScEGGYgRvgWNIzgFFtnAc4M0TClnRU5LPYWGe/ch70J+KaM/9St+DAIrVBKrWwHZJEgMLZIGq3m7EWgVQ/bbffbufcl+8QZtZPlekZMO9ziSwQcWMzjSeVeVNbKD81f/92Df+B3ESMoEBNhN2gAAAABJRU5ErkJggg==',
			'B29B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7QgMYQxhCGUMdkMQCprC2Mjo6OgQgi7WKNLo2BDqIoKhjAIsFILkvNGrV0pWZkaFZSO4DqpvCEBKIZh5DAAO6ea2MDowYdrA2oLslNEA01AHNzQMVflSEWNwHAM23zKGvUj3cAAAAAElFTkSuQmCC',
			'A09E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7GB0YAhhCGUMDkMRYAxhDGB0dHZDViUxhbWVtCEQRC2gVaXRFiIGdFLV02srMzMjQLCT3gdQ5hKDqDQ0FimGYx9rKiCGG6ZaAVkw3D1T4URFicR8AKzfKEX0IjRcAAAAASUVORK5CYII=',
			'B722' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7QgNEQx1CGaY6IIkFTGFodHR0CAhAFmtlaHRtCHQQQVXXCiQbRJDcFxq1atqqlVmropDcB1QXAFTZiGJHK6MDA1g/shhrA1DlFBSxKSINQJUBqG4WaWANDQwNGQThR0WIxX0A4i7NRb8BAtUAAAAASUVORK5CYII=',
			'0AE9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7GB0YAlhDHaY6IImxBjCGsDYwBAQgiYlMYW1lBaoWQRILaBVpdEWIgZ0UtXTaytTQVVFhSO6DqGOYiqpXNNQVZC6KHWB1KHawBoDFUNwCtLHRFc3NAxV+VIRY3AcA/rPLloyGAYwAAAAASUVORK5CYII=',
			'00C0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXElEQVR4nGNYhQEaGAYTpIn7GB0YAhhCHVqRxVgDGEMYHQKmOiCJiUxhbWVtEAgIQBILaBVpdAWaIILkvqil01amrlqZNQ3JfWjqcIphswObW7C5eaDCj4oQi/sAwlHLETTAx5IAAAAASUVORK5CYII=',
			'064E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7GB0YQxgaHUMDkMRYA1hbGVodHZDViUwRaWSYiioW0CrSwBAIFwM7KWrptLCVmZmhWUjuC2gVbWVtxNDb6BoaiGGHA5o6sFvQxLC5eaDCj4oQi/sAsxPKSBJq5okAAAAASUVORK5CYII=',
			'1EFA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWUlEQVR4nGNYhQEaGAYTpIn7GB1EQ1lDA1qRxVgdRBpYGximOiCJiULEAgJQ9ILEQCTCfSuzpoYtDV2ZNQ3JfWjqkMVCQ3Cbh1NMNAToZjSxgQo/KkIs7gMAfAzHdFAPM0YAAAAASUVORK5CYII=',
			'3B8B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAVElEQVR4nGNYhQEaGAYTpIn7RANEQxhCGUMdkMQCpoi0Mjo6OgQgq2wVaXRtCHQQQRZDVQd20sqoqWGrQleGZiG7j1jzsIhhcws2Nw9U+FERYnEfAPIlyz6GoTwfAAAAAElFTkSuQmCC',
			'8F18' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7WANEQx2mMEx1QBITmSLSwBDCEBCAJBbQKtLAGMLoIIKubgpcHdhJS6Omhq2atmpqFpL70NTBzWOYgmoeNjFselkDgG4JdUBx80CFHxUhFvcBAPKczBFs1bAGAAAAAElFTkSuQmCC',
			'C3A9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7WENYQximMEx1QBITaRVpZQhlCAhAEgtoZGh0dHR0EEEWa2BoZW0IhImBnRS1alXY0lVRUWFI7oOoC5iKprfRNTSgQQTNDteGABQ7QG4B6kVxC8jNIPOQ3TxQ4UdFiMV9ABIbzTLOq8NKAAAAAElFTkSuQmCC',
			'A4FD' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7GB0YWllDA0MdkMRYAximsgJlApDERKYwhILERJDEAloZXZHEwE6KWgoEoSuzpiG5L6BVpBVdb2ioaKgrhnkMGOpgYgGYYihuHqjwoyLE4j4A1xfKmOp0rNAAAAAASUVORK5CYII=',
			'36A5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7RAMYQximMIYGIIkFTGFtZQhldEBR2SrSyOjoiCo2RaSBtSHQ1QHJfSujpoUtXRUZFYXsvimirawNAQ0iaOa5hmIRawh0EEFzC1BvALL7QG4Gik11GAThR0WIxX0AQjvL6LHBKFkAAAAASUVORK5CYII=',
			'4470' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpI37pjC0soYGtKKIhTBMZWgImOqAJMYYwhAKFAsIQBJjncLoytDo6CCC5L5p05YuXbV0ZdY0JPcFTBFpZZjCCFMHhqGhoqEOAahiILcwOjCg2AF2XwMDilugYqhuHqjwox7E4j4AfhzLjSMBpE8AAAAASUVORK5CYII=',
			'E4BB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7QkMYWllDGUMdkMQCGhimsjY6OgSgioWyNgQ6iKCIMboiqQM7KTRq6dKloStDs5DcF9Ag0oppnmioK4Z5QLdgE0PTi83NAxV+VIRY3AcAA/TM6dYXr5AAAAAASUVORK5CYII=',
			'D257' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7QgMYQ1hDHUNDkMQCprC2sgJpEWSxVpFGVwwxhkbXqUAayX1RS1ctXZqZtTILyX1AdVPAJKreAJBNqGKMDqwNAQEMqG5pYHR0dEB1s2ioQygjithAhR8VIRb3AQDjd80/MBlmRQAAAABJRU5ErkJggg==',
			'9584' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpIn7WANEQxlCGRoCkMREpog0MDo6NCKLBbSKNLACSTSxEKC6KQFI7ps2derSVaGroqKQ3MfqytDo6OjogKyXoZWh0bUhMDQESUygVQQoFoDmFtZWoB0oYqwBjCHobh6o8KMixOI+AMoczXutYmM2AAAAAElFTkSuQmCC',
			'C8C0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7WEMYQxhCHVqRxURaWVsZHQKmOiCJBTSKNLo2CAQEIIs1sLayNjA6iCC5L2rVyrClq1ZmTUNyH5o6qBjIPDQxLHZgcws2Nw9U+FERYnEfAJHmzIXDCQ4LAAAAAElFTkSuQmCC',
			'59AA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7QkMYQximMLQiiwU0sLYyhDJMdUARE2l0dHQICEASCwwQaXRtCHQQQXJf2LSlS1NXRWZNQ3ZfK2MgkjqoGEOja2hgaAiyHa0sjejqRKawtrKiibEGMIagiw1U+FERYnEfAPwWzLq7jqAsAAAAAElFTkSuQmCC',
			'C99E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7WEMYQxhCGUMDkMREWllbGR0dHZDVBTSKNLo2BKKKNaCIgZ0UtWrp0szMyNAsJPcFNDAGOoSg62VodEA3r5Gl0RFNDJtbsLl5oMKPihCL+wBMpcqpiFoJHQAAAABJRU5ErkJggg==',
			'2EFC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7WANEQ1lDA6YGIImJTBFpYG1gCBBBEgtoBYkxOrAg64aKobhv2tSwpaErs1DcF4CiDgwZHTDFWBsw7RBpwHRLaCjQzQ0MKG4eqPCjIsTiPgDJ1slp6sWctgAAAABJRU5ErkJggg==',
			'406F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpI37pjAEMIQyhoYgi4UwhjA6Ojogq2MMYW1lbUAVY50i0ujawAgTAztp2rRpK1OnrgzNQnJfAEgdmnmhoSC9gQ6obgHZgS6G6Raom1HFBir8qAexuA8AF+rI78Cqr+EAAAAASUVORK5CYII=',
			'E642' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7QkMYQxgaHaY6IIkFNLC2MrQ6BASgiIk0Mkx1dBBBFWtgCHRoEEFyX2jUtLCVmVmropDcF9Ag2sra6NDogGaea2hAKwOaGFDVFAZ0tzQ6BGC62TE0ZBCEHxUhFvcBAOqjzmSbOF+ZAAAAAElFTkSuQmCC',
			'0324' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7GB1YQxhCGRoCkMRYA0RaGR0dGpHFRKYwNLo2BLQiiwW0MrQCySkBSO6LWroqbNXKrKgoJPeB1bUyOqDpbXSYwhgagmaHQwAWtzigioHczBoagCI2UOFHRYjFfQDj3sy85bDVoAAAAABJRU5ErkJggg==',
			'661D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7WAMYQximMIY6IImJTGFtZQhhdAhAEgtoEWlkBIqJIIs1AHlT4GJgJ0VGTQtbNW1l1jQk94VMEW1FUgfR2yrS6ECEGNgtU1DdAnIzY6gjipsHKvyoCLG4DwBRMsrcny90mgAAAABJRU5ErkJggg==',
			'B215' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7QgMYQximMIYGIIkFTGFtZQhhdEBWF9Aq0uiILjaFodFhCqOrA5L7QqNWLV01bWVUFJL7gOpAsEEExTyGAEwxoPlTGB1EUN3SAFQXgOy+0ADRUMdQh6kOgyD8qAixuA8AK1vMX63uY4gAAAAASUVORK5CYII=',
			'6064' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7WAMYAhhCGRoCkMREpjCGMDo6NCKLBbSwtrI2OLSiiDWINLo2MEwJQHJfZNS0lalTV0VFIbkvZApQnaOjA4reVpDewNAQFDGQHQHY3IIihs3NAxV+VIRY3AcAHVbNz+U2sKAAAAAASUVORK5CYII=',
			'42A3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAeklEQVR4nGNYhQEaGAYTpI37pjCGMExhCHVAFgthbWUIZXQIQBJjDBFpdHR0aBBBEmOdwtDo2hDQEIDkvmnTVi1duipqaRaS+wKmMExhRagDw9BQhgDW0AAU84BucQCpQxVjbWBtCERxC8MU0VCgvahuHqjwox7E4j4ANjzNTYWt1cYAAAAASUVORK5CYII=',
			'BAE8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7QgMYAlhDHaY6IIkFTGEMYW1gCAhAFmtlbWVtYHQQQVEn0uiKUAd2UmjUtJWpoaumZiG5D00d1DzRUFd081pB6vDaAXUzUAzNzQMVflSEWNwHAA/8zfc+Os4zAAAAAElFTkSuQmCC',
			'7D64' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7QkNFQxhCGRoCkEVbRVoZHR0a0cQaXRscWlHEpoDEGKYEILsvatrK1KmroqKQ3MfoAFTn6OiArJe1AaQ3MDQESUwELBaA4paABrBb0MSwuHmAwo+KEIv7AOOFzoIQFvoLAAAAAElFTkSuQmCC',
			'DB95' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QgNEQxhCGUMDkMQCpoi0Mjo6OiCrC2gVaXRtCEQXa2VtCHR1QHJf1NKpYSszI6OikNwHUscQEtAggmaeQwOmmCPQDhEMtzgEILsP4maGqQ6DIPyoCLG4DwCZH82BK1EMCgAAAABJRU5ErkJggg==',
			'58B2' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7QkMYQ1hDGaY6IIkFNLC2sjY6BASgiIk0ujYEOoggiQUGgNU1iCC5L2zayrCloatWRSG7rxWsrhHZDoZWkHkBrchuCYCITUEWE5kCcQuyGGsAyM2MoSGDIPyoCLG4DwDyyc1ytp+d3QAAAABJRU5ErkJggg==',
			'AC51' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7GB0YQ1lDHVqRxVgDWBtdGximIouJTBFpAIqFIosFtIo0sE5lgOkFOylq6bRVSzOzliK7D6QORCLrDQ3FFAOpc8UQY210dHRAE2MMBbokNGAQhB8VIRb3AQALms01HZGjIwAAAABJRU5ErkJggg==',
			'6EF8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXElEQVR4nGNYhQEaGAYTpIn7WANEQ1lDA6Y6IImJTBFpYG1gCAhAEgtoAYkxOoggizWgqAM7KTJqatjS0FVTs5DcF4LNvFYs5mERw+YWsJsbGFDcPFDhR0WIxX0Ae13Ll+BT390AAAAASUVORK5CYII=',
			'ADEE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWElEQVR4nGNYhQEaGAYTpIn7GB1EQ1hDHUMDkMRYA0RaWYEyyOpEpog0uqKJBbSiiIGdFLV02srU0JWhWUjuQ1MHhqGhBM2DiWG4JaAV080DFX5UhFjcBwAi9srTZDbrrwAAAABJRU5ErkJggg==',
			'1F54' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7GB1EQ11DHRoCkMRYHUQaWBsYGpHFRCFirQEoeoFiUxmmBCC5b2XW1LClmVlRUUjuA6ljaAh0QNcLFAsNQTcP6BJ0dYyOqO4TDQHqDWVAERuo8KMixOI+AHGCytdV4kC6AAAAAElFTkSuQmCC',
			'FE3B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAUUlEQVR4nGNYhQEaGAYTpIn7QkNFQxmB0AFJLKBBpIG10dEhAE2MoSHQQQRdDKEO7KTQqKlhq6auDM1Cch+aOvzmYRHDdAummwcq/KgIsbgPAF8ZzS99tF8KAAAAAElFTkSuQmCC',
			'853E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7WANEQxmBMABJTGSKSANro6MDsrqAVhEgGYgiBlQXwoBQB3bS0qipS1dNXRmaheQ+kSkMjQ4Y5gHF0MwD2oEhJjKFtRXdLawBjCHobh6o8KMixOI+ABtBy2EWv7frAAAAAElFTkSuQmCC',
			'C854' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7WEMYQ1hDHRoCkMREWllbWRsYGpHFAhpFGl0bGFpRxBqA6qYyTAlAcl/UqpVhSzOzoqKQ3AdSx9AQ6ICqV6TRoSEwNATDjgAMtzA6oroP5GaGUAYUsYEKPypCLO4DAMcszlC3wKKfAAAAAElFTkSuQmCC',
			'95FB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7WANEQ1lDA0MdkMREpog0sDYwOgQgiQW0QsREUMVCkNSBnTRt6tSlS0NXhmYhuY/VlaHRFc08hlaIGLJ5Aq0iGGIiU1hb0d3CGsAIshfFzQMVflSEWNwHAD5Gyouzgzd7AAAAAElFTkSuQmCC',
			'0BDE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWklEQVR4nGNYhQEaGAYTpIn7GB1EQ1hDGUMDkMRYA0RaWRsdHZDViUwRaXRtCEQRC2gFqkOIgZ0UtXRq2NJVkaFZSO5DUwcTwzAPmx3Y3ILNzQMVflSEWNwHAGBXysj1c0aJAAAAAElFTkSuQmCC',
			'D9A0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7QgMYQximMLQiiwVMYW1lCGWY6oAs1irS6OjoEBCAJubaEOggguS+qKVLl6auisyahuS+gFbGQCR1UDGGRtdQdDEWoHkBqHYA3cLaEIDiFpCbWUEmDILwoyLE4j4AU8nO6PBvEv0AAAAASUVORK5CYII=',
			'6CF6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7WAMYQ1lDA6Y6IImJTGFtdG1gCAhAEgtoEWlwbWB0EEAWaxBpYAWKIbsvMmraqqWhK1OzkNwXMgWsDtW8VoheETQxVzQxbG4Bu7mBAcXNAxV+VIRY3AcAEDrMFU7KjEIAAAAASUVORK5CYII=',
			'8261' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7WAMYQxhCGVqRxUSmsLYyOjpMRRYLaBVpdG1wCEVVxwAUg+sFO2lp1KqlS6euWorsPqC6KayODq2o5jEEsIJIFDFGB3QxoFsaGNH0sgaIhgJdEhowCMKPihCL+wAjncwz533FFgAAAABJRU5ErkJggg==',
			'0617' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7GB0YQximMIaGIImxBrC2MoQwNIggiYlMEWlkRBMLaAXypgBpJPdFLZ0WtmraqpVZSO4LaBVtBaprZUDV2+gwBaQb1Q6gWAADulumMDqgu5kx1BFFbKDCj4oQi/sAUUnKnZ16gAAAAAAASUVORK5CYII=',
			'ED5A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7QkNEQ1hDHVqRxQIaRFpZGximOqCKNbo2MAQEoItNZXQQQXJfaNS0lamZmVnTkNwHUufQEAhThywWGoJhB4a6VkZHRxQxkJsZQhlRxAYq/KgIsbgPAPuwzXYEdcmDAAAAAElFTkSuQmCC',
			'CA60' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpIn7WEMYAhhCGVqRxURaGUMYHR2mOiCJBTSytrI2OAQEIIs1iDS6NjA6iCC5L2rVtJWpU1dmTUNyH1idoyNMHVRMNNS1IRBVrBFkXgCKHSKtIo2OaG5hDRFpdEBz80CFHxUhFvcBAB20zUSR1SQLAAAAAElFTkSuQmCC',
			'9483' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7WAMYWhlCGUIdkMREpjBMZXR0dAhAEgsAqmJtCGgQQRFjdGV0dGgIQHLftKlLl64KXbU0C8l9rK4irUjqILBVNNQVzTyBVoZWdDuAbmlFdws2Nw9U+FERYnEfAMpQy+AvnoZUAAAAAElFTkSuQmCC',
			'B915' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7QgMYQximMIYGIIkFTGFtZQhhdEBWF9Aq0uiILjZFpNFhCqOrA5L7QqOWLs2atjIqCsl9AVMYAx2mMDSIoJjH0IgpxgIyz0EE3S1TGAKQ3QdyM2Oow1SHQRB+VIRY3AcAr0TM0cG9kyEAAAAASUVORK5CYII=',
			'90B2' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7WAMYAlhDGaY6IImJTGEMYW10CAhAEgtoZW1lbQh0EEERE2l0bXRoEEFy37Sp01amhq5aFYXkPlZXsLpGZDsYQHqBJiC7RQBsR8AUBixuwXQzY2jIIAg/KkIs7gMAtl7Mf0xBsP4AAAAASUVORK5CYII=',
			'E5D1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7QkNEQ1lDGVqRxQIaRBpYGx2mYog1BISiiYUAxWB6wU4KjZq6dOmqqKXI7gtoYGh0RajDIyaCRYy1FegWFLHQEMYQoJtDAwZB+FERYnEfAOLPznSyvDopAAAAAElFTkSuQmCC',
			'5A96' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7QkMYAhhCGaY6IIkFNDCGMDo6BASgiLG2sjYEOgggiQUGiDS6AsWQ3Rc2bdrKzMzI1Cxk97WKNDqEBKKYx9AqGuoA1CuCbAdQnSOamMgUoBiaW1iB9jqguXmgwo+KEIv7AHhMzJwuQt/IAAAAAElFTkSuQmCC',
			'900A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7WAMYAhimMLQii4lMYQxhCGWY6oAkFtDK2sro6BAQgCIm0ujaEOggguS+aVOnrUxdFZk1Dcl9rK4o6iAQojc0BElMAGyHI4o6iFsYUcQgbkYVG6jwoyLE4j4AjDDKpINwg6cAAAAASUVORK5CYII=',
			'9F05' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7WANEQx2mMIYGIImJTBFpYAhldEBWF9Aq0sDo6IghxtoQ6OqA5L5pU6eGLV0VGRWF5D5WV5C6gAYRZJtbMcUEoHaIYLiFIQDZfawBQLEpDFMdBkH4URFicR8AoLvLBH5mdSkAAAAASUVORK5CYII=',
			'39B9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7RAMYQ1hDGaY6IIkFTGFtZW10CAhAVtkq0ujaEOgggiw2BSjW6AgTAztpZdTSpamhq6LCkN03hTHQtdFhKoreVgageQENqGIsIDEUO7C5BZubByr8qAixuA8AA0PM5ZUVbXEAAAAASUVORK5CYII=',
			'49A3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAeElEQVR4nGNYhQEaGAYTpI37pjCGMExhCHVAFgthbWUIZXQIQBJjDBFpdHR0aBBBEmOdItLo2hDQEIDkvmnTli5NXRW1NAvJfQFTGAOR1IFhaChDo2toAIp5DFNYwOahirG2sjYEorgF5GbWhgBUNw9U+FEPYnEfALolzb//uiWNAAAAAElFTkSuQmCC',
			'8382' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7WANYQxhCGaY6IImJTBFpZXR0CAhAEgtoZWh0bQh0EEFRxwBS1yCC5L6lUavCVoWuWhWF5D6oukYHDPOAJKbYFAYsbsF0M2NoyCAIPypCLO4DAFpczFSUtD8EAAAAAElFTkSuQmCC',
			'7717' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7QkNFQx2mMIaGIIu2MjQ6hDA0iKCJOaKLTQGKTmFoCEB2X9SqaUC4MgvJfYwODAFgtUh6WUGiU0C6EVAEKAoUCUAWCwDZOAWoFk2MMdQRRWygwo+KEIv7APQaywETxHIDAAAAAElFTkSuQmCC',
			'37EB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7RANEQ11DHUMdkMQCpjA0ujYwOgQgq2yFiIkgi01haGVFqAM7aWXUqmlLQ1eGZiG7bwpDACuGeYwOrOjmAU1DFwuYItKArlc0ACiG5uaBCj8qQizuAwAtkspusnlucQAAAABJRU5ErkJggg==',
			'AAEF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAW0lEQVR4nGNYhQEaGAYTpIn7GB0YAlhDHUNDkMRYAxhDWEEySGIiU1hb0cUCWkUaXRFiYCdFLZ22MjV0ZWgWkvvQ1IFhaKhoKLoYNnU4xUIdUcQGKvyoCLG4DwA+QMpHN2IGoQAAAABJRU5ErkJggg==',
			'4BBB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXUlEQVR4nGNYhQEaGAYTpI37poiGsIYyhjogi4WItLI2OjoEIIkxhog0ujYEOoggibFOQVEHdtK0aVPDloauDM1Ccl/AFEzzQkMxzWOYglUMQy9WNw9U+FEPYnEfAC1bzFOX5a+hAAAAAElFTkSuQmCC',
			'B772' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7QgNEQ11DA6Y6IIkFTGFodGgICAhAFmsFiQU6iKCqA4uKILkvNGrVtFVLV62KQnIfUF0AA9hMZPMYHYCirQwoYqwNQNEpKGJTRBpYG4AqUdwMEmMMDRkE4UdFiMV9AOhnzeuqYsOgAAAAAElFTkSuQmCC',
			'57B4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nM2QvQ2AQAhGsWCDc5+zuB4TsXAaKNjg4gY2N6WU+FNqFBryAvlegHYpgT/1K3489VwYhALzWYtmvTAhi2wkMNRcKfjNa1s3bssS/QwIdcjxFqzLKCNPMcNQ0JPiXqpJPOPAkJydnL/634N947cDyNPO1BLfzCMAAAAASUVORK5CYII=',
			'D65A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7QgMYQ1hDHVqRxQKmsLayNjBMdUAWaxVpBIoFBKCKNbBOZXQQQXJf1NJpYUszM7OmIbkvoFUUaH4gTB3cPIeGwNAQNDFXdHVAtzA6OqKIgdzMEMqIIjZQ4UdFiMV9AD3WzOqU9SRMAAAAAElFTkSuQmCC',
			'4803' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpI37pjCGMExhCHVAFgthbWUIZXQIQBJjDBFpdHR0aBBBEmOdwtrK2hDQEIDkvmnTVoYtXRW1NAvJfQGo6sAwNFSk0RUoIoLiFkw7GKZgugWrmwcq/KgHsbgPAIaqzJyNqEiyAAAAAElFTkSuQmCC',
			'C9DB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7WEMYQ1hDGUMdkMREWllbWRsdHQKQxAIaRRpdGwIdRJDFGiBiAUjui1q1dGnqqsjQLCT3BTQwBiKpg4oxYJrXyIIhhs0t2Nw8UOFHRYjFfQBHt80O+XKm7QAAAABJRU5ErkJggg==',
			'DCAA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7QgMYQxmmMLQiiwVMYW10CGWY6oAs1irS4OjoEBCAJsbaEOggguS+qKXTVi1dFZk1Dcl9aOoQYqGBoSFoYq7o6oBuQRcDuRndvIEKPypCLO4DACJszpSRO87EAAAAAElFTkSuQmCC',
			'8DA6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7WANEQximMEx1QBITmSLSyhDKEBCAJBbQKtLo6OjoIICqrtG1IdAB2X1Lo6atTF0VmZqF5D6oOgzzXEMDHUTQxRpQxUBuYW0IQNELcjNQDMXNAxV+VIRY3AcAw8/Nuz7qr8wAAAAASUVORK5CYII=',
			'B556' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7QgNEQ1lDHaY6IIkFTBFpYG1gCAhAFmsFiTE6CKCqC2GdyuiA7L7QqKlLl2ZmpmYhuS9gCkOjQ0MgmnlgMQcRVDsaXdHFprC2Mjo6oOgNDWAMYQhlQHHzQIUfFSEW9wEAhtPNVDRKJEAAAAAASUVORK5CYII=',
			'E122' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QkMYAhhCGaY6IIkFNDAGMDo6BASgiLEGsDYEOoigiAH1AkkRJPeFRq2KWrUyC0gg3AdW18rQ6ICudwpQFF0MJIomxugAsgfZzayhrKGBoSGDIPyoCLG4DwBnacqj+CNyAgAAAABJRU5ErkJggg=='        
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