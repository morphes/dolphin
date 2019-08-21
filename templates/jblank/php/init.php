<?php
defined('_JEXEC') or die;
!version_compare(PHP_VERSION, '5.3.10', '=>') or die('Your host needs to use PHP 5.3.10 or higher to run JBlank Template');
require_once dirname(__FILE__) . '/libs/template.php';
$tpl = JBlankTemplate::getInstance();
$tpl
    // enable or disable debug mode. Default in Joomla configuration.php
    //->debug(true)

    // include CSS files if it's not empty
    // compile less *.file to CSS and cache it
    // compile scss *.file to CSS and cache it (experimental!)
    ->css(array(
        'bootstrap.css',
        'style.css',
        'less.css',
        'jquery.jscrollpane.css',
        'media_response.css',
        '_font-awesome.min.css',
    ))

    // include JavaScript files
    ->js(array(
        'jquery-2.1.4.min.js',
        'jquery.flexslider-min.js',
        'include_plugins.js',
        'function.js',
        'jquery.jscrollpane.min.js',
        'jquery.mousewheel.js',
        'slick.min.js',
        'owl.carousel.js',
        'jquery.fancybox.js',
    ))

    // exclude css files from system or components (experimental!)
    ->excludeCSS(array(
        // 'regex pattern or filename',
        // 'jbzoo\.css',
    ))

    // exclude JS files from system or components (experimental!)
    ->excludeJS(array(
        // 'regex pattern or filename',
        'mootools',
        'media\/jui\/js',        
        'media\/system\/js',
    ))

    // set custom generator
    ->generator('Dolphinarium')// null for disable

    // set HTML5 mode (for <head> tag)
    ->html5(true)

    // add custom meta tags
    ->meta(array(
        // template customization
        '<meta http-equiv="X-UA-Compatible" content="IE=edge">',
        '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">',

        // apple icons     
        '<link rel="apple-touch-icon" sizes="57x57" href="' . $tpl->img . '/icons/apple-touch-icon-57x57.png">',
        '<link rel="apple-touch-icon" sizes="60x60" href="' . $tpl->img . '/icons/apple-touch-icon-60x60.png">',
        '<link rel="apple-touch-icon" sizes="72x72" href="' . $tpl->img . '/icons/apple-touch-icon-72x72.png">',
        '<link rel="apple-touch-icon" sizes="76x76" href="' . $tpl->img . '/icons/apple-touch-icon-76x76.png">',
        '<link rel="apple-touch-icon" sizes="114x114" href="' . $tpl->img . '/icons/apple-touch-icon-114x114.png">',
        '<link rel="apple-touch-icon" sizes="120x120" href="' . $tpl->img . '/icons/apple-touch-icon-120x120.png">',
        '<link rel="apple-touch-icon" sizes="144x144" href="' . $tpl->img . '/icons/apple-touch-icon-144x144.png">',
        '<link rel="apple-touch-icon" sizes="152x152" href="' . $tpl->img . '/icons/apple-touch-icon-152x152.png">',
        '<link rel="icon" type="image/png" href="' . $tpl->img . '/icons/favicon-32x32.png" sizes="32x32">',
        '<link rel="icon" type="image/png" href="' . $tpl->img . '/icons/favicon-96x96.png" sizes="96x96">',
        '<link rel="icon" type="image/png" href="' . $tpl->img . '/icons/favicon-16x16.png" sizes="16x16">',
        '<link rel="manifest" href="' . $tpl->img . '/icons/manifest.json">',
        '<link rel="mask-icon" href="' . $tpl->img . '/icons/safari-pinned-tab.svg" color="#5bbad5">',
        '<link rel="shortcut icon" href="' . $tpl->img . '/icons/favicon.ico">',
        '<meta name="msapplication-TileColor" content="#da532c">',
        '<meta name="msapplication-TileImage" content="' . $tpl->img . '/icons/mstile-144x144.png">',
        '<meta name="msapplication-config" content="' . $tpl->img . '/icons/browserconfig.xml">',
        '<meta name="theme-color" content="#ffffff">',
    ));

/************************* your php code below this line **************************************************************/


// USE IT ON YOUR OWN --> RISK <-- THIS IS EXPERIMENTAL FEATURES!
// After that all assets files will be included

/* 
$tpl
    // merge css with compress (second arg)
    ->merge('css', true);
    // merge js with compress (second arg)
   ->merge('js', true);
*/
