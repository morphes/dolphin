<?php
defined('_JEXEC') or die;
require dirname(__FILE__) . '/php/init.php';
//$this->setHeadData($headlink);
if (isset($this->_script['text/javascript']))
    {
    $this->_script['text/javascript'] = preg_replace('%jQuery\(window\)\.on\(\'load\',\s*function\(\)\s*{\s*new\s*JCaption\(\'img.caption\'\);\s*}\);\s*%', '', $this->_script['text/javascript']); 
    if (empty($this->_script['text/javascript']))
        unset($this->_script['text/javascript']); 
    }
?>
<?php echo $tpl->renderHTML(); ?>
<head> 

    <jdoc:include type="head" />
    <script type="text/javascript">
    $(document).ready(function() {
        $('.fancybox').fancybox();
    });
</script>
 </head>
<body class="<?php echo $tpl->getBodyClasses(); ?>">
    <header>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <jdoc:include type="modules" name="schedule-top" />
                    <ul class="social_header">
                        <li>
                            <a href="https://www.facebook.com/dolphinevpatoria">
                                <i class="fa fa-facebook"></i>
                            </a>
                        </li>
                        <li>
                            <a href="https://ok.ru/dolphinevpatoria">
                                <i class="fa fa-odnoklassniki"></i>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.instagram.com/dolphinevpatoria/">
                                <i class="fa fa-instagram"></i>
                            </a>
                        </li>
                        <li>
                            <a href="http://vk.com/dolphinevpatoria">
                                <i class="fa fa-vk"></i>
                            </a>
                        </li>
                    </ul>
                    <div class="phone_header">
                        <a href="/kontakty.html">
                            <span>
                                Контакты
                            </span>
                        </a>
                        <a href="tel:+79788554651" class='link_phone'>
                            <i></i>
                            +7 (978) 855-46-51
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <?php if ($tpl->isFront()) { ?>  
    <div class="slider_wrap">
        <div class="flexslider">
            <nav>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="logo">
                                <a href="/">
                                    <img src="/templates/jblank/svg/logo.svg" alt="">
                                </a>
                            </div>
                            <jdoc:include type="modules" name="mainmenu" />
                        </div>
                    </div>
                </div>
            </nav>
            <jdoc:include type="modules" name="slider" />
        </div>
    </div>
    <section class='section_location'>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 left_location">
                    <jdoc:include type="modules" name="schedule-module" />
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 right_location">
                    <jdoc:include type="modules" name="contact-module" /> 
                </div>
            </div>
        </div>
    </section>
    <?php } else { ?> 
    <nav class='inner_nav'>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="logo">
                        <a href="/">
                            <img src="/templates/jblank/svg/logo.svg" alt="">
                        </a>
                    </div>
                    <jdoc:include type="modules" name="mainmenu" />
                </div>
            </div>
        </div>
    </nav>

<?php if ($this->countModules('breadcrumbs')): ?>
    <div class="title_inner">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <jdoc:include type="modules" name="breadcrumbs" />
                    <h1> 
                        <?php
                        $delimiter_pos = 0;
                        $delimiter_pos = stripos($this->getTitle(), ' - ') + 3;
                        echo substr($this->getTitle(), $delimiter_pos);
                        ?>
                    </h1>
                    <jdoc:include type="modules" name="submenu" />
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

    <section class="section_inner">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <jdoc:include type="component" />
                    <?php if ($this->countModules('right')): ?>
                        <div class="saitbar">
                            <jdoc:include type="modules" name="right" />
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class='section_location section_location_prise'>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 left_location">
                        <jdoc:include type="modules" name="schedule-module" />
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 right_location">
                        <jdoc:include type="modules" name="contact-module" />                        
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php } ?>
    <jdoc:include type="modules" name="homeservices" />
    <jdoc:include type="modules" name="homenews" />
    <jdoc:include type="modules" name="instagram" />
    <iframe src='/inwidget/index.php?width=800&inline=3&view=10&toolbar=false&preview=fullsize' scrolling='no' frameborder='no' class='insta'></iframe>
    <footer>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">  

                <form class="dispatch" method="POST" action="https://cp.unisender.com/ru/subscribe?hash=5dk4h78cgmquc3epbdcc3k5t4srkekypdijgq41mfusq6wuepsn1o" name="subscribtion_form">
                    <div class="text_input">
                        <input class="subscribe-form-item__control subscribe-form-item__control--input-email" placeholder='Подписаться на рассылку' name="email" value="" type="text">
                    </div>
                        <input class="subscribe-form-item__btn subscribe-form-item__btn--btn-submit" value="" type="submit">
                    <input name="charset" value="UTF-8" type="hidden">
                    <input name="default_list_id" value="6993466" type="hidden">
                    <input name="overwrite" value="2" type="hidden">
                    <input name="is_v5" value="1" type="hidden">
                </form>
                    <hr>
                    <jdoc:include type="modules" name="footermenu"  />
                    <jdoc:include type="modules" name="copyright" />
                </div>
            </div>
        </div>
    </footer>
<!-- Yandex.Metrika counter --> <script type="text/javascript"> (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter31558823 = new Ya.Metrika({ id:31558823, clickmap:true, trackLinks:true, accurateTrackBounce:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks"); </script> <noscript><div><img src="https://mc.yandex.ru/watch/31558823" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter -->
</body></html>