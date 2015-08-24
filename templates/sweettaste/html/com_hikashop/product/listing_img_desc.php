<?php
/**
 * @package	HikaShop for Joomla!
 * @version	2.3.4
 * @author	hikashop.com
 * @copyright	(C) 2010-2014 HIKARI SOFTWARE. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');

$height = $this->newSizes->height;
$width = $this->newSizes->width;
$link = hikashop_contentLink('product&task=show&cid='.$this->row->product_id.'&name='.$this->row->alias.$this->itemid.$this->category_pathway,$this->row);

if(!empty($this->row->extraData->top)) { echo implode("\r\n",$this->row->extraData->top); }

if($this->config->get('thumbnail',1)){
    if($this->params->get('link_to_product_page',1)){ ?>        
        <a href="<?php echo $link; ?>" title="<?php echo $this->escape($this->row->product_name); ?>" class="thumbnail">
	<!-- PRODUCT IMG -->
    <?php }
	$image_options = array('default' => true,'forcesize'=>$this->config->get('image_force_size',true),'scale'=>$this->config->get('image_scale_mode','inside'));
	$img = $this->image->getThumbnail(@$this->row->file_path, array('width' => $this->image->main_thumbnail_x, 'height' => $this->image->main_thumbnail_y), $image_options);
	if($img->success) {
		echo '<img class="hikashop_product_listing_image" title="'.$this->escape(@$this->row->file_description).'" alt="'.$this->escape(@$this->row->file_name).'" src="'.$img->url.'"/>';
	}
	$main_thumb_x = $this->image->main_thumbnail_x;
	$main_thumb_y = $this->image->main_thumbnail_y;
	if($this->params->get('display_badges',1)){
		$this->classbadge->placeBadges($this->image, $this->row->badges, -10, 0);
	}
	$this->image->main_thumbnail_x = $main_thumb_x;
	$this->image->main_thumbnail_y = $main_thumb_y; ?>  
    <!-- EO PRODUCT IMG -->
    <div class="caption">
        <!-- PRODUCT MANUFACTURER -->
        <h3>        	
        	<?php        		 
        		if($this->config->get('manufacturer_display', 0) && !empty($this->row->product_manufacturer_id)){
				$class = hikashop_get('class.category');
				$manufacturer = $class->get($this->row->product_manufacturer_id);				
				echo $manufacturer->category_name;
			} ?>
        </h3>
        <!-- EO PRODUCT MANUFACTURER -->
        
        <p>
            <!-- PRODUCT NAME -->
            <?php echo $this->row->product_name.'<br>'; ?>
            <!-- EO PRODUCT NAME -->
            
            <!-- PRODUCT DESCRIPTION -->            
            <?php echo preg_replace('#<hr *id="system-readmore" */>.*#is','',$this->row->product_description); ?>            
            <!-- EO PRODUCT DESCRIPTION -->
        </p>    
            <!--PRODUCT PRICE-->
            <?php            	
                if($this->params->get('show_price','-1')=='-1'){
                    $config =& hikashop_config();
                    $this->params->set('show_price',$config->get('show_price'));
                }
                if($this->params->get('show_price')){
                    $this->setLayout('listing_price');
                    echo $this->loadTemplate();
                } 
             ?>
             <!--EO PRODUCT PRICE-->
            
            
            <?php if ($this->config->get('show_code')) { ?>
                <!-- PRODUCT CODE -->
                <span class='hikashop_product_code_list'>
                <?php if($this->params->get('link_to_product_page',1)){ ?>
                    <a href="<?php echo $link;?>">
                <?php }
                echo $this->row->product_code;
                if($this->params->get('link_to_product_page',1)){ ?>
                    </a>
                <?php } ?>
                </span>
                <!-- EO PRODUCT CODE -->
            <?php } ?>            
                        
            <?php
                if($this->params->get('add_to_cart') || $this->params->get('add_to_wishlist')){ ?>
                <!-- ADD TO CART BUTTON AREA -->
                <?php
                    $this->setLayout('add_to_cart_listing');
                    echo $this->loadTemplate(); ?>
                <!-- EO ADD TO CART BUTTON AREA -->
                <?php
                }
            ?>
            
            <?php
                if($this->params->get('show_vote_product')){ ?>
                <!-- PRODUCT VOTE -->
                <?php
                    $this->setLayout('listing_vote');
                    echo $this->loadTemplate(); ?>
                <!-- EO PRODUCT VOTE -->
                <?php
                } 
            ?>
            
            <?php
                if(JRequest::getVar('hikashop_front_end_main',0) && JRequest::getVar('task')=='listing' && $this->params->get('show_compare')) { ?>
                    <!-- COMPARISON AREA -->
                    <br/><?php
                    if( $this->params->get('show_compare') == 1 ) {
                        $js = 'setToCompareList('.$this->row->product_id.',\''.$this->escape($this->row->product_name).'\',this); return false;';
                        echo $this->cart->displayButton(JText::_('ADD_TO_COMPARE_LIST'),'compare',$this->params,$link,$js,'',0,1,'hikashop_compare_button');
                    } else { ?>
                        <input type="checkbox" class="hikashop_compare_checkbox" id="hikashop_listing_chk_<?php echo $this->row->product_id;?>" onchange="setToCompareList(<?php echo $this->row->product_id;?>,'<?php echo $this->escape($this->row->product_name); ?>',this);"><label for="hikashop_listing_chk_<?php echo $this->row->product_id;?>"><?php echo JText::_('ADD_TO_COMPARE_LIST'); ?></label>                            
                    <?php } ?>
                    <!-- EO COMPARISON AREA -->
                <?php
                } 
            ?>  
    </div>
    <?php 
        if($this->params->get('link_to_product_page',1)){ ?> 
            </a><?php 
        } 
    }
    ?>
		
<?php if(!empty($this->row->extraData->bottom)) { echo implode("\r\n",$this->row->extraData->bottom); } ?>
