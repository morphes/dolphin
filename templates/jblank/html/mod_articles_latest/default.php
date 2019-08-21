<?php
defined('_JEXEC') or die;
?>




    <div class='home_news'>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="links_news">
                        <a href="/novosti/novosti-delfinariya.html">
                            <span>
                                Новости дельфинария
                            </span>
                        </a>
                    </div>
                    <div class="scroll_news">
                        <div class='wr_sc'>
                            <ul class='ul_news clone_news'>
                            	<?php foreach ($list as $item) :  ?>
                                <li>
                                    <div class="img_news">
                                    	<a href="<?php echo $item->link; ?>"><?php $images = json_decode($item->images); ?><img class="image_intro" src="<?php echo $images->image_intro; ?>" alt="<?php echo $images->image_intro_alt; ?>"/></a>	
                                    </div>
                                    <div class="link_news_li">
										<a href="<?php echo $item->link; ?>">
											<span>
												<?php echo $item->title; ?>
											</span>
										</a>
                                    </div>
                                    <p>
                                        <?php echo substr(strip_tags($item->introtext), 0, strpos(strip_tags($item->introtext), ' ', 300)).'...' ; ?>
                                    </p>
                                    <div class="date">
                                        <?php echo JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC3')); ?>
                                    </div>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
