<?php
defined('_JEXEC') or die;

JHtml::_('behavior.core');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
?>
<?php if (empty($this->items)) : ?>
	<p> <?php echo JText::_('COM_CONTACT_NO_CONTACTS'); ?>	 </p>
<?php else : ?>
		<h3>
            Где находимся
        </h3>
		<ul class="location_address">
			<?php foreach ($this->items as $i => $item) : ?>

				<?php if (in_array($item->access, $this->user->getAuthorisedViewLevels())) : ?>
					<li class="cat-list-row<?php echo $i % 2; ?>" >
                        <h4>
                            <?php echo $item->name; ?>
                        </h4>
                        <?php if ($this->params->get('show_suburb_headings') AND !empty($item->suburb)) : ?>
                            <div class="link_address">
                                <a href="/kontakty.html">
                                    <span>
                                        <?php echo $item->suburb . ', '; ?>
                                    </span>
                                </a>
                            </div>
                        <?php endif; ?>
                        <p>
                            Телефон:
                            
							<?php if ($this->params->get('show_telephone_headings') AND !empty($item->telephone)) : ?>
								<a href="tel:<?php echo JText::sprintf($item->telephone); ?>">
									<?php echo JText::sprintf($item->telephone); ?>
                            	</a>
                            <?php endif; ?>

							<?php if ($this->params->get('show_mobile_headings') AND !empty ($item->mobile)) : ?>
								, <a href="tel:<?php echo JText::sprintf($item->telephone); ?>">
									<?php echo JText::sprintf($item->mobile); ?>
								</a>
							<?php endif; ?>
                        </p>

					</li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>


<?php endif; ?>
