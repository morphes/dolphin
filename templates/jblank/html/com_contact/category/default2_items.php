<?php
defined('_JEXEC') or die;

JHtml::_('behavior.core');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
?>
<?php if (empty($this->items)) : ?>
	<p> <?php echo JText::_('COM_CONTACT_NO_CONTACTS'); ?>	 </p>
<?php else : ?>

					<ul class="maps_ul">


						<?php foreach ($this->items as $i => $item) : ?>

							<?php if (in_array($item->access, $this->user->getAuthorisedViewLevels())) : ?>
								<li>
									<div class="conttact_text">
				                        <div class="text_contact">
				                            <?php echo $item->name; ?>
				                        </div>
				                        <div class="location_contain">
					                        <?php if ($this->params->get('show_suburb_headings') AND !empty($item->suburb)) : ?>
					                            <p>
													<i class="local"></i>
					                                <?php echo $item->suburb; ?>					                           
					                            </p>
					                        <?php endif; ?>
					                        <p>					                           		                            
												<?php if ($this->params->get('show_mobile_headings') AND !empty($item->mobile)) : ?>
													<i class="tel"></i>
													<a href="tel:<?php echo JText::sprintf($item->mobile); ?>">
														<?php echo JText::sprintf($item->mobile); ?>
					                            	</a>
					                            <?php endif; ?>

												<?php if ($this->params->get('show_telephone_headings') AND !empty ($item->telephone)) : ?>
													, <a href="tel:<?php echo JText::sprintf($item->telephone); ?>">
														<?php echo JText::sprintf($item->telephone); ?>
													</a>
												<?php endif; ?>
					                        </p>
				                    	</div>
			                    	</div>
					                <div class="maps_map">
					                	<?php echo $item->misc; ?>
 									</div>
								</li>
							<?php endif; ?>
						<?php endforeach; ?>						
					</ul>


	
<?php endif; ?>
